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

        // Allow any authenticated user in local environment (configurable)
        if (app()->isLocal() && config('admin.allow_any_auth_in_local')) {
            return $next($request);
        }

        // Check by email list from config / .env
        $adminEmails = config('admin.emails', []);

        // Fallback to default seed admin email if none configured
        if (empty($adminEmails)) {
            $adminEmails = ['admin@cvbuilder.com'];
        }

        if (!in_array(strtolower($user->email), array_map('strtolower', $adminEmails))) {
            abort(403, 'Access denied. Admin access required.');
        }

        return $next($request);
    }
}
