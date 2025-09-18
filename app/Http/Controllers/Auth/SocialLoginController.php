<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

class SocialLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Redirect user to Google OAuth page
     */
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            Log::error('Google OAuth redirect failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Google login is currently unavailable. Please try again later.');
        }
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists with this email
            $existingUser = User::where('email', $googleUser->getEmail())->first();
            
            if ($existingUser) {
                // Update Google ID if not set
                if (!$existingUser->google_id) {
                    $existingUser->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }
                
                Auth::login($existingUser);
                
                Log::info('User logged in via Google', [
                    'user_id' => $existingUser->id,
                    'email' => $existingUser->email,
                    'authenticated' => Auth::check()
                ]);
                
                return redirect('/user');
            }
            
            // Create new user
            $newUser = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(32)), // Random password for social users
            ]);
            
            Auth::login($newUser);
            
            Log::info('New user created via Google', [
                'user_id' => $newUser->id,
                'email' => $newUser->email,
                'authenticated' => Auth::check()
            ]);
            
            return redirect('/user')
                ->with('success', 'Welcome to CV Builder! Your account has been created successfully.');
                
        } catch (\Exception $e) {
            Log::error('Google OAuth callback failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Google login failed. Please try again or use email/password login.');
        }
    }

    /**
     * Redirect user to LinkedIn OAuth page
     */
    public function redirectToLinkedIn()
    {
        try {
            return Socialite::driver('linkedin')->redirect();
        } catch (\Exception $e) {
            Log::error('LinkedIn OAuth redirect failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'LinkedIn login is currently unavailable. Please try again later.');
        }
    }

    /**
     * Handle LinkedIn OAuth callback
     */
    public function handleLinkedInCallback()
    {
        try {
            $linkedinUser = Socialite::driver('linkedin')->user();
            
            // Check if user already exists with this email
            $existingUser = User::where('email', $linkedinUser->getEmail())->first();
            
            if ($existingUser) {
                // Update LinkedIn ID if not set
                if (!$existingUser->linkedin_id) {
                    $existingUser->update([
                        'linkedin_id' => $linkedinUser->getId(),
                        'avatar' => $linkedinUser->getAvatar(),
                    ]);
                }
                
                Auth::login($existingUser);
                
                Log::info('User logged in via LinkedIn', [
                    'user_id' => $existingUser->id,
                    'email' => $existingUser->email,
                    'authenticated' => Auth::check()
                ]);
                
                return redirect('/user');
            }
            
            // Create new user
            $newUser = User::create([
                'name' => $linkedinUser->getName(),
                'email' => $linkedinUser->getEmail(),
                'linkedin_id' => $linkedinUser->getId(),
                'avatar' => $linkedinUser->getAvatar(),
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(32)), // Random password for social users
            ]);
            
            Auth::login($newUser);
            
            Log::info('New user created via LinkedIn', [
                'user_id' => $newUser->id,
                'email' => $newUser->email,
                'authenticated' => Auth::check()
            ]);
            
            return redirect('/user')
                ->with('success', 'Welcome to CV Builder! Your account has been created successfully.');
                
        } catch (\Exception $e) {
            Log::error('LinkedIn OAuth callback failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'LinkedIn login failed. Please try again or use email/password login.');
        }
    }
}
