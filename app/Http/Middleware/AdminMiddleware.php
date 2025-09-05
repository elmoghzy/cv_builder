<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user is admin (you can customize this logic)
        $user = auth()->user();
        
        // Method 1: Check by role (recommended)
        if ($user->hasRole('admin')) {
            return $next($request);
        }
        
        // Method 2: Fallback - Check by email for existing admins
        $adminEmails = [
            'admin@cvbuilder.com',
            'cv_builder@gmail.com',  // This is the existing admin user
            'moh@admin.com',
            'test@admin.com'
        ];

        if (!in_array($user->email, $adminEmails)) {
            abort(403, 'Access denied. Admin access required.');
        }

        return $next($request);
    }
}
