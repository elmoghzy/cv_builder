<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            $socialUser = Socialite::driver($provider)->user();
            
            // Check if user exists with this social provider
            $user = User::where('provider', $provider)
                       ->where('provider_id', $socialUser->getId())
                       ->first();

            if ($user) {
                // User exists, log them in
                Auth::login($user, true);
                return redirect()->intended(url('/user'));
            }

            // Check if user exists with same email
            $existingUser = User::where('email', $socialUser->getEmail())->first();

            if ($existingUser) {
                // Link social account to existing user
                $existingUser->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
                
                Auth::login($existingUser, true);
                return redirect()->intended(url('/user'))
                    ->with('success', 'Social account linked successfully!');
            }

            // Create new user
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'password' => Hash::make(Str::random(16)), // Random password for social users
                'email_verified_at' => now(),
                'is_active' => true,
            ]);

            // Assign user role
            $user->assignRole('user');

            Auth::login($user, true);
            
            return redirect()->intended(url('/user'))
                ->with('success', 'Account created successfully! Welcome to CV Builder Egypt!');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Something went wrong during authentication. Please try again.');
        }
    }
}
