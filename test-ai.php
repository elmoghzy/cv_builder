<?php

require_once 'vendor/autoload.php';

// Load Laravel configuration
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test AI service
$aiService = app(\App\Services\AiCvContentService::class);

echo "AI Service Enabled: " . ($aiService->enabled() ? 'Yes' : 'No') . "\n";

try {
    $response = $aiService->generateSimpleText('Generate a professional summary for a software engineer');
    echo "AI Response: " . $response . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
