<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayMob Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for PayMob payment gateway integration.
    | Get these values from your PayMob dashboard.
    |
    */

    'api_key' => env('PAYMOB_API_KEY'),
    'integration_id' => env('PAYMOB_INTEGRATION_ID'),
    'iframe_id' => env('PAYMOB_IFRAME_ID'),
    'hmac_secret' => env('PAYMOB_HMAC_SECRET'),

    // Environment settings
    'environment' => env('PAYMOB_ENVIRONMENT', 'sandbox'), // 'sandbox' or 'production'
    
    // API URLs
    'base_url' => env('PAYMOB_BASE_URL', 'https://accept.paymob.com/api'),
    'iframe_base_url' => env('PAYMOB_IFRAME_BASE_URL', 'https://accept.paymob.com/api/acceptance/iframes'),

    // Payment settings
    'currency' => env('PAYMOB_CURRENCY', 'EGP'),
    'default_amount' => 100.00, // Default CV price in EGP
    
    // Callback and webhook URLs
    'callback_url' => env('PAYMOB_CALLBACK_URL', env('APP_URL') . '/payment/callback'),
    'webhook_url' => env('PAYMOB_WEBHOOK_URL', env('APP_URL') . '/payment/webhook'),
    
    // Timeout settings
    'timeout' => env('PAYMOB_TIMEOUT', 30), // HTTP request timeout in seconds
    'payment_expiry' => env('PAYMOB_PAYMENT_EXPIRY', 3600), // Payment expiry in seconds (1 hour)
    
    // Error handling
    'max_retries' => env('PAYMOB_MAX_RETRIES', 3),
    'retry_delay' => env('PAYMOB_RETRY_DELAY', 1), // Delay between retries in seconds
    
    // Logging
    'log_requests' => env('PAYMOB_LOG_REQUESTS', true),
    'log_responses' => env('PAYMOB_LOG_RESPONSES', true),
    // Price for a CV in cents (EGP). Example: 5000 = 50 EGP
    'cv_price_cents' => env('PAYMOB_CV_PRICE_CENTS', 5000),
];
