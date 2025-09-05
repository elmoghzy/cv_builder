<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayMobService
{
    private $apiKey;
    private $integrationId;
    private $iframeId;
    private $hmacSecret;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('paymob.api_key');
        $this->integrationId = config('paymob.integration_id');
        $this->iframeId = config('paymob.iframe_id');
        $this->hmacSecret = config('paymob.hmac_secret');
        $this->baseUrl = 'https://accept.paymob.com/api';
    }

    /**
     * Get authentication token
     */
    private function getAuthToken(): string
    {
        $response = Http::post($this->baseUrl . '/auth/tokens', [
            'api_key' => $this->apiKey
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to authenticate with PayMob');
        }

        return $response->json('token');
    }

    /**
     * Create order
     */
    private function createOrder(string $authToken, array $orderData): array
    {
        $response = Http::post($this->baseUrl . '/ecommerce/orders', [
            'auth_token' => $authToken,
            'delivery_needed' => false,
            'amount_cents' => $orderData['amount'],
            'currency' => $orderData['currency'],
            'items' => $orderData['items'],
            'merchant_order_id' => $orderData['order_id']
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to create PayMob order: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Get payment key
     */
    private function getPaymentKey(string $authToken, array $paymentData): string
    {
        $response = Http::post($this->baseUrl . '/acceptance/payment_keys', [
            'auth_token' => $authToken,
            'amount_cents' => $paymentData['amount'],
            'expiration' => 3600, // 1 hour
            'order_id' => $paymentData['order_id'],
            'billing_data' => $paymentData['billing_data'],
            'currency' => $paymentData['currency'],
            'integration_id' => $this->integrationId,
            'lock_order_when_paid' => true
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to get PayMob payment key: ' . $response->body());
        }

        return $response->json('token');
    }

    /**
     * Create complete payment
     */
    public function createPayment(array $paymentData): array
    {
        try {
            // Step 1: Get auth token
            $authToken = $this->getAuthToken();
            Log::info('PayMob auth token obtained');

            // Step 2: Create order
            $orderResponse = $this->createOrder($authToken, $paymentData);
            Log::info('PayMob order created', ['order_id' => $orderResponse['id']]);

            // Step 3: Get payment key
            $paymentKey = $this->getPaymentKey($authToken, array_merge($paymentData, [
                'order_id' => $orderResponse['id']
            ]));
            Log::info('PayMob payment key obtained');

            return [
                'id' => $orderResponse['id'],
                'payment_key' => $paymentKey,
                'order' => $orderResponse
            ];

        } catch (\Exception $e) {
            Log::error('PayMob payment creation failed', [
                'error' => $e->getMessage(),
                'payment_data' => $paymentData
            ]);
            throw $e;
        }
    }

    /**
     * Get iframe URL for payment
     */
    public function getIframeUrl(string $paymentKey): string
    {
        return "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentKey}";
    }

    /**
     * Verify callback HMAC
     */
    public function verifyCallback(array $callbackData): bool
    {
        if (!isset($callbackData['hmac'])) {
            return false;
        }

        $receivedHmac = $callbackData['hmac'];
        
        // Prepare data for HMAC calculation (as per PayMob documentation)
        $dataToHash = [
            $callbackData['amount_cents'] ?? '',
            $callbackData['created_at'] ?? '',
            $callbackData['currency'] ?? '',
            $callbackData['error_occured'] ?? 'false',
            $callbackData['has_parent_transaction'] ?? 'false',
            $callbackData['id'] ?? '',
            $callbackData['integration_id'] ?? '',
            $callbackData['is_3d_secure'] ?? 'false',
            $callbackData['is_auth'] ?? 'false',
            $callbackData['is_capture'] ?? 'false',
            $callbackData['is_refunded'] ?? 'false',
            $callbackData['is_standalone_payment'] ?? 'false',
            $callbackData['is_voided'] ?? 'false',
            $callbackData['order']['id'] ?? '',
            $callbackData['owner'] ?? '',
            $callbackData['pending'] ?? 'false',
            $callbackData['source_data']['pan'] ?? '',
            $callbackData['source_data']['sub_type'] ?? '',
            $callbackData['source_data']['type'] ?? '',
            $callbackData['success'] ?? 'false'
        ];

        $hashString = implode('', $dataToHash);
        $calculatedHmac = hash_hmac('sha512', $hashString, $this->hmacSecret);

        return hash_equals($calculatedHmac, $receivedHmac);
    }

    /**
     * Verify webhook HMAC (similar to callback but might have different structure)
     */
    public function verifyWebhook(array $webhookData): bool
    {
        // PayMob webhooks use the same HMAC verification as callbacks
        return $this->verifyCallback($webhookData);
    }

    /**
     * Refund transaction
     */
    public function refundTransaction(string $transactionId, int $amountCents, string $reason = 'Customer request'): array
    {
        try {
            $authToken = $this->getAuthToken();

            $response = Http::post($this->baseUrl . '/acceptance/void_refund/refund', [
                'auth_token' => $authToken,
                'transaction_id' => $transactionId,
                'amount_cents' => $amountCents,
                'refund_reason' => $reason
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to process refund: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('PayMob refund failed', [
                'transaction_id' => $transactionId,
                'amount_cents' => $amountCents,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get transaction details
     */
    public function getTransaction(string $transactionId): array
    {
        try {
            $authToken = $this->getAuthToken();

            $response = Http::get($this->baseUrl . '/acceptance/transactions/' . $transactionId, [
                'auth_token' => $authToken
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get transaction details: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Failed to get PayMob transaction', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get payment statistics
     */
    public function getPaymentStats(array $filters = []): array
    {
        // This would require implementing PayMob's reporting API
        // For now, return basic stats from local database
        return [
            'total_transactions' => \App\Models\Payment::count(),
            'successful_payments' => \App\Models\Payment::where('status', 'success')->count(),
            'failed_payments' => \App\Models\Payment::where('status', 'failed')->count(),
            'total_revenue' => \App\Models\Payment::where('status', 'success')->sum('amount'),
        ];
    }
}