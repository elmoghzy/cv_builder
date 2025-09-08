<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\Payment;
use App\Services\CvService;
use App\Services\PayMobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PayMobService $payMobService;

    public function __construct(PayMobService $payMobService)
    {
        $this->payMobService = $payMobService;
    }

    public function callback(Request $request)
    {
        $data = $request->all();

        // Verify HMAC (skip in local for easier testing)
        if (!app()->isLocal() && !$this->payMobService->verifyCallback($data)) {
            return redirect()->route('filament.user.resources.cvs.index')
                ->with('error', 'Payment verification failed.');
        }

        $success = filter_var($data['success'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $orderId = $data['order']['id'] ?? $data['order_id'] ?? $data['merchant_order_id'] ?? null;

        if (!$orderId) {
            return redirect()->route('filament.user.resources.cvs.index')->with('error', 'Invalid payment response.');
        }

        $orderId = (string) $orderId;

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            Log::warning('Payment record not found for order', ['order_id' => $orderId, 'callback_data' => $data]);
            return redirect()->route('filament.user.resources.cvs.index')->with('error', 'Payment record not found.');
        }

        if ($success) {
            $payment->update([
                'status' => 'success',
                'transaction_id' => (string)($data['id'] ?? $data['transaction_id'] ?? ''),
                'paid_at' => now(),
            ]);

            // Mark CV as paid
            if ($payment->cv) {
                try {
                    app(CvService::class)->markAsPaid($payment->cv, $payment->id);
                } catch (\Throwable $e) {
                    // Fallback: still mark as paid even if PDF generation fails
                    Log::error('PDF generation failed after payment', ['error' => $e->getMessage()]);
                    $payment->cv->update([
                        'is_paid' => true,
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);
                }
            }

            // Redirect back to the CV preview page
            if ($payment->cv) {
                return redirect()->route('cv.preview', $payment->cv)->with('success', 'Payment successful! Your PDF is ready.');
            }

            return redirect()->route('filament.user.resources.cvs.index')->with('success', 'Payment successful! Your PDF is ready.');
        }

        $payment->update(['status' => 'failed']);
        if ($payment->cv) {
            return redirect()->route('cv.preview', $payment->cv)->with('error', 'Payment failed.');
        }
        return redirect()->route('filament.user.resources.cvs.index')->with('error', 'Payment failed.');
    }

    /**
     * Get payment history for user
     */
    public function history()
    {
        $payments = \App\Models\Payment::where('user_id', Auth::id())
            ->with('cv')
            ->latest()
            ->paginate(15);

        return view('payment.history', compact('payments'));
    }

    /**
     * Webhook for PayMob notifications (optional)
     */
    public function webhook(Request $request)
    {
        try {
            // Verify webhook authenticity
            $isValid = $this->payMobService->verifyWebhook($request->all());

            if (!$isValid) {
                Log::warning('Invalid PayMob webhook received');
                return response('Unauthorized', 401);
            }

            // Process webhook data (similar to callback)
            $webhookData = $request->all();
            Log::info('PayMob webhook received', $webhookData);

            // Update payment status if needed (optional redundancy)
            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'webhook_data' => $request->all()
            ]);

            return response('Error', 500);
        }
    }

    /**
     * Initiate a payment for a given CV and redirect to the PayMob iframe.
     */
    public function initiate(Request $request, Cv $cv)
    {
        // Ensure the user owns the CV
        if ($cv->user_id !== Auth::id()) {
            abort(403);
        }

        // If already paid, go back to preview
        if ($cv->is_paid) {
            return redirect()->route('cv.preview', $cv)->with('info', 'This CV is already paid.');
        }

        try {
            $amountCents = (int) config('paymob.cv_price_cents', 5000);
            $orderId = 'cv-' . $cv->id . '-' . time();

            $billing = [
                'apartment' => 'NA', 'email' => Auth::user()->email, 'floor' => 'NA',
                'first_name' => Auth::user()->name, 'street' => 'NA', 'building' => 'NA',
                'phone_number' => 'NA', 'shipping_method' => 'NA', 'postal_code' => 'NA',
                'city' => 'Cairo', 'country' => 'EG', 'last_name' => Auth::user()->name, 'state' => 'Cairo'
            ];

            $res = $this->payMobService->createPayment([
                'amount' => $amountCents,
                'currency' => config('paymob.currency', 'EGP'),
                'items' => [[
                    'name' => 'CV Purchase',
                    'amount_cents' => $amountCents,
                    'quantity' => 1,
                ]],
                'order_id' => $orderId,
                'billing_data' => $billing,
            ]);

            Payment::create([
                'user_id' => Auth::id(),
                'cv_id' => $cv->id,
                'order_id' => (string)($res['id'] ?? $orderId),
                'amount' => $amountCents / 100,
                'currency' => config('paymob.currency', 'EGP'),
                'status' => 'pending',
                'paymob_data' => $res,
            ]);

            $iframeUrl = $this->payMobService->getIframeUrl($res['payment_key']);
            return redirect()->away($iframeUrl);
        } catch (\Throwable $e) {
            Log::error('Payment initiation failed', [
                'cv_id' => $cv->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('cv.preview', $cv)->with('error', 'Failed to initiate payment. Please try again.');
        }
    }

    /**
     * Optional explicit success endpoint.
     */
    public function success(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        // Mark as success if not already
        if ($payment->status !== 'success') {
            $payment->update(['status' => 'success', 'paid_at' => now()]);
        }

        if ($payment->cv && !$payment->cv->is_paid) {
            try {
                app(CvService::class)->markAsPaid($payment->cv, $payment->id);
            } catch (\Throwable $e) {
                Log::error('PDF generation failed after success endpoint', ['error' => $e->getMessage()]);
            }
        }

        return redirect()->route('cv.preview', $payment->cv)->with('success', 'Payment successful!');
    }

    /**
     * Optional explicit failed endpoint.
     */
    public function failed(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        $payment->update(['status' => 'failed']);

        return redirect()->route('cv.preview', $payment->cv)->with('error', 'Payment failed.');
    }
}
