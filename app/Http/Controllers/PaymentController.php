<?php

namespace App\Http\Controllers;

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
                'status' => 'paid',
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

            return redirect()->route('filament.user.resources.cvs.index')->with('success', 'Payment successful! Your PDF is ready.');
        }

        $payment->update(['status' => 'failed']);
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
}
