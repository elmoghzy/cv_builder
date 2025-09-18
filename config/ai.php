<?php

return [
    'enabled' => (bool) (env('OPENAI_API_KEY') || env('GEMINI_API_KEY')),
    'provider' => env('AI_PROVIDER', 'gemini'),
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        'timeout' => (int) env('OPENAI_TIMEOUT', 20),
    ],
    // Default language for AI outputs (override via AI_LANGUAGE)
    'language' => env('AI_LANGUAGE', 'en'),
];
