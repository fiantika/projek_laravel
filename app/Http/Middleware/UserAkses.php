<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if(auth()->user()->role == $role){
            return $next($request);
        }
        // Redirect unauthorized users to the operator dashboard instead of the
        // (removed) admin dashboard
        return redirect('/operator/dashboard');
    }
}
