<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupGoogleOauth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:setup-google {--client-id= : Google Client ID} {--client-secret= : Google Client Secret} {--url= : App URL}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Google OAuth credentials for social login';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Setting up Google OAuth for CV Builder...');
        $this->newLine();

        // Get parameters
        $clientId = $this->option('client-id') ?: $this->ask('Enter Google Client ID');
        $clientSecret = $this->option('client-secret') ?: $this->secret('Enter Google Client Secret');
        $appUrl = $this->option('url') ?: $this->ask('Enter App URL', config('app.url', 'http://localhost:8000'));

        if (!$clientId || !$clientSecret) {
            $this->error('❌ Client ID and Client Secret are required!');
            return 1;
        }

        // Update .env file
        $envPath = base_path('.env');
        if (!File::exists($envPath)) {
            $this->error('❌ .env file not found!');
            return 1;
        }

        $env = File::get($envPath);

        // Update or add Google OAuth settings
        $env = $this->updateEnvVariable($env, 'GOOGLE_CLIENT_ID', $clientId);
        $env = $this->updateEnvVariable($env, 'GOOGLE_CLIENT_SECRET', $clientSecret);
        $env = $this->updateEnvVariable($env, 'GOOGLE_REDIRECT_URI', $appUrl . '/auth/google/callback');
        $env = $this->updateEnvVariable($env, 'APP_URL', $appUrl);

        File::put($envPath, $env);

        // Clear config cache
        $this->call('config:clear');
        $this->call('cache:clear');

        $this->newLine();
        $this->info('✅ Google OAuth setup completed successfully!');
        $this->newLine();
        
        $this->line('<info>Configuration:</info>');
        $this->line("• Client ID: {$clientId}");
        $this->line("• App URL: {$appUrl}");
        $this->line("• Redirect URI: {$appUrl}/auth/google/callback");
        $this->newLine();

        $this->line('<comment>Next steps:</comment>');
        $this->line('1. Make sure your Google Cloud Console OAuth client has the correct redirect URI:');
        $this->line("   {$appUrl}/auth/google/callback");
        $this->line('2. Test the login by visiting: ' . $appUrl . '/login');
        $this->line('3. Click on the Google login button');
        $this->newLine();

        $this->line('<comment>Troubleshooting:</comment>');
        $this->line('• If you get "redirect_uri_mismatch", check your Google Console settings');
        $this->line('• If you get "client_id not found", verify your Client ID');
        $this->line('• Read GOOGLE_OAUTH_SETUP.md for detailed instructions');

        return 0;
    }

    private function updateEnvVariable($env, $key, $value)
    {
        $pattern = "/^{$key}=.*/m";
        $replacement = "{$key}={$value}";

        if (preg_match($pattern, $env)) {
            return preg_replace($pattern, $replacement, $env);
        } else {
            return $env . "\n{$replacement}";
        }
    }
}
