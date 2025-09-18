<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

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

        /** @var User $user */
        $user = auth()->user();

        // Check if user has admin role using hasRole method
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Check configured admin emails as fallback
        $adminEmails = config('admin.emails', []);
        if (empty($adminEmails)) {
            $adminEmails = ['admin@cvbuilder.com'];
        }

        if (in_array(strtolower($user->email), array_map('strtolower', $adminEmails))) {
            return $next($request);
        }

        // If user is not admin, redirect to user panel instead of showing 403
        return redirect('/user')->with('error', 'ليس لديك صلاحية للوصول إلى لوحة الإدارة. تم توجيهك إلى لوحة المستخدمين.');
    }
}
