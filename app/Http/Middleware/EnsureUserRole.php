<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {


        // Allow guest users to reach login page
        if (!auth()->check()) {
            return redirect('/');
        }

        // Only allow authenticated admins
        if (auth()->user()->role !== 'user') {
            auth()->logout(); // Log them out to prevent stuck session
            return redirect('/')->withErrors(['email' => 'Login as admin required.']);
        }

        return $next($request);
    }

}
