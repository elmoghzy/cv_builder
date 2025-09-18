<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect to social provider
     */
    public function redirectToProvider(string $provider)
    {
        if (!in_array($provider, ['google', 'linkedin'])) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle social provider callback
     */
    public function handleProviderCallback(string $provider)
    {
        if (!in_array($provider, ['google', 'linkedin'])) {
            abort(404);
        }

        try {
            Log::info('Handling Google callback.');
            $socialUser = Socialite::driver($provider)->user();
            Log::info('Socialite user retrieved:', ['email' => $socialUser->getEmail(), 'id' => $socialUser->getId()]);
            
            // Define provider column based on provider
            $providerColumn = $provider === 'google' ? 'google_id' : 'linkedin_id';
            
            // Check if user exists with this social provider
            $user = User::where($providerColumn, $socialUser->getId())->first();

            if ($user) {
                // User exists, log them in
                Log::info('User found by provider ID. Logging in.', ['user_id' => $user->id]);
                Auth::login($user, true);
                return redirect('/user');
            }

            Log::info('User not found by provider ID. Checking by email.');
            // Check if user exists with same email
            $existingUser = User::where('email', $socialUser->getEmail())->first();

            if ($existingUser) {
                // Link social account to existing user
                Log::info('Existing user found by email. Linking account.', ['user_id' => $existingUser->id]);
                $existingUser->update([
                    $providerColumn => $socialUser->getId(),
                ]);
                
                Auth::login($existingUser, true);
                return redirect('/user');
            }

            Log::info('No existing user found. Creating new user.');
            // Create new user
            $userData = [
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                $providerColumn => $socialUser->getId(),
                'password' => Hash::make(Str::random(16)), // Random password for social users
                'email_verified_at' => now(),
            ];

            $user = User::create($userData);
            Log::info('New user created.', ['user_id' => $user->id]);

            // Assign user role if Spatie roles are available
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('user');
                Log::info('Assigned "user" role.');
            }

            Auth::login($user, true);
            Log::info('New user logged in. Redirecting to /user.');
            
            return redirect('/user');

        } catch (\Exception $e) {
            // Log the actual error for debugging
            Log::error('Google OAuth Error: ' . $e->getMessage(), [
                'provider' => $provider,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Something went wrong during authentication. Please try again. Error: ' . $e->getMessage());
        }
    }
}
