<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access to login and logout routes even when not authenticated
        $allowedRoutes = [
            'admin.login',
            'admin.logout',
        ];

        if (!auth()->check()) {
            if ($request->routeIs(...$allowedRoutes)) {
                return $next($request);
            }

            return redirect('/');
        }

        // Authenticated but not admin
        if (auth()->user()->role !== 'admin') {
            auth()->logout(); // Prevent stuck session
            return redirect('/')->withErrors(['email' => 'Not authorized as admin.']);
        }

        return $next($request);
    }



}
