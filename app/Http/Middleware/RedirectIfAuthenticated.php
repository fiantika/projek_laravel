<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Redirect authenticated users based on their role. The "admin"
                // role has been merged into the operator role, so only
                // operator and keuangan roles are handled here.
                if ($user->role === 'operator') {
                    return redirect('/operator/dashboard');
                } elseif ($user->role === 'keuangan') {
                    return redirect('/kasir/dashboard');
                } else {
                    // Fallback: send unknown roles to login page
                    return redirect('/login');
                }
            }
        }

        return $next($request);
    }
}
