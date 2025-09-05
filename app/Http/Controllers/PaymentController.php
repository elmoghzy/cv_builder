<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\Payment;
use App\Services\PayMobService;
use App\Jobs\GeneratePdfJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $payMobService;

    public function __construct(PayMobService $payMobService)
    {
        $this->payMobService = $payMobService;
        $this->middleware('auth');
    }

    /**
     * Initiate payment for CV download
     */
    public function initiate(Request $request, Cv $cv)
    {
        // Verify user owns the CV
        if ($cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to CV');
        }

        // Check if CV is already paid
        if ($cv->is_paid) {
            return redirect()
                ->route('cv.download', $cv)
                ->with('info', 'CV is already paid. You can download it now.');
        }

        try {
            // Create internal payment record
            $orderId = 'CV_' . $cv->id . '_' . time() . '_' . Str::random(6);
            
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'cv_id' => $cv->id,
                'order_id' => $orderId,
                'amount' => 100.00, // EGP 100
                'currency' => 'EGP',
                'status' => 'pending',
                'transaction_id' => '', // Will be updated from PayMob
            ]);

            // Prepare payment data for PayMob
            $paymentData = [
                'order_id' => $orderId,
                'amount' => 10000, // PayMob expects amount in cents (100 EGP = 10000 cents)
                'currency' => 'EGP',
                'billing_data' => [
                    'first_name' => Auth::user()->name,
                    'last_name' => '',
                    'email' => Auth::user()->email,
                    'phone_number' => Auth::user()->phone ?? '+20123456789',
                    'apartment' => 'N/A',
                    'floor' => 'N/A',
                    'street' => 'N/A',
                    'building' => 'N/A',
                    'shipping_method' => 'N/A',
                    'postal_code' => 'N/A',
                    'city' => 'Cairo',
                    'country' => 'EG',
                    'state' => 'Cairo'
                ],
                'items' => [
                    [
                        'name' => 'CV Download - ' . $cv->title,
                        'amount_cents' => 10000,
                        'description' => 'Professional ATS-compliant CV download',
                        'quantity' => 1
                    ]
                ]
            ];

            // Create PayMob payment
            $paymentResponse = $this->payMobService->createPayment($paymentData);

            if (!$paymentResponse || !isset($paymentResponse['payment_key'])) {
                throw new \Exception('Failed to create PayMob payment');
            }

            // Update payment with PayMob transaction ID
            $payment->update([
                'transaction_id' => $paymentResponse['id'] ?? $paymentResponse['order']['id'] ?? '',
                'paymob_data' => $paymentResponse,
            ]);

            // Store payment ID in session for callback verification
            session(['payment_id' => $payment->id]);

            // Redirect to PayMob iframe
            $iframeUrl = $this->payMobService->getIframeUrl($paymentResponse['payment_key']);
            
            return redirect($iframeUrl);

        } catch (\Exception $e) {
            Log::error('Payment initiation failed', [
                'cv_id' => $cv->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('cv.preview', $cv)
                ->with('error', 'Failed to initiate payment. Please try again.');
        }
    }

    /**
     * Handle PayMob callback
     */
    public function callback(Request $request)
    {
        try {
            // Verify callback authenticity
            $isValidCallback = $this->payMobService->verifyCallback($request->all());
            
            if (!$isValidCallback) {
                Log::warning('Invalid PayMob callback received', $request->all());
                return redirect()
                    ->route('dashboard')
                    ->with('error', 'Invalid payment callback.');
            }

            $callbackData = $request->all();
            $orderId = $callbackData['order']['merchant_order_id'] ?? null;
            $transactionStatus = $callbackData['success'] ?? false;
            $transactionId = $callbackData['id'] ?? null;

            // Find payment by order ID
            $payment = Payment::where('order_id', $orderId)->first();

            if (!$payment) {
                Log::error('Payment not found for callback', ['order_id' => $orderId]);
                return redirect()
                    ->route('dashboard')
                    ->with('error', 'Payment record not found.');
            }

            // Update payment status
            $newStatus = $transactionStatus ? 'success' : 'failed';
            
            $payment->update([
                'status' => $newStatus,
                'transaction_id' => $transactionId,
                'paymob_data' => array_merge($payment->paymob_data ?? [], $callbackData),
                'paid_at' => $transactionStatus ? now() : null,
                'payment_method' => $callbackData['source_data']['type'] ?? 'card',
            ]);

            if ($transactionStatus) {
                // Payment successful - dispatch PDF generation job
                GeneratePdfJob::dispatch($payment->cv, $transactionId);
                
                Log::info('Payment successful, PDF generation job dispatched', [
                    'payment_id' => $payment->id,
                    'cv_id' => $payment->cv_id,
                    'transaction_id' => $transactionId
                ]);

                return redirect()
                    ->route('payment.success', $payment)
                    ->with('success', 'Payment successful! Your CV is being prepared for download.');
            } else {
                // Payment failed
                Log::info('Payment failed', [
                    'payment_id' => $payment->id,
                    'reason' => $callbackData['data']['message'] ?? 'Unknown error'
                ]);

                return redirect()
                    ->route('payment.failed', $payment)
                    ->with('error', 'Payment failed. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('Payment callback processing failed', [
                'error' => $e->getMessage(),
                'callback_data' => $request->all()
            ]);

            return redirect()
                ->route('dashboard')
                ->with('error', 'Payment processing failed. Please contact support.');
        }
    }

    /**
     * Payment success page
     */
    public function success(Payment $payment)
    {
        // Verify user owns this payment
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payment.success', compact('payment'));
    }

    /**
     * Payment failed page
     */
    public function failed(Payment $payment)
    {
        // Verify user owns this payment
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payment.failed', compact('payment'));
    }

    /**
     * Get payment history for user
     */
    public function history()
    {
        $payments = Auth::user()
            ->payments()
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

            // Update payment status if needed
            // This is redundant if callback is working properly
            // but serves as backup notification method

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
