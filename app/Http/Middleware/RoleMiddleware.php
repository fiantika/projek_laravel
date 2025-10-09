<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        if (auth()->check()) {
            $userRole = auth()->user()->role;
            // Define mapping: treat 'admin' as 'operator' and 'kasir' as 'keuangan'
            $synonyms = [
                'admin'   => 'operator',
                'operator' => 'operator',
                'keuangan' => 'keuangan',
                'kasir'   => 'keuangan',
            ];
            $normalizedUserRole = $synonyms[$userRole] ?? $userRole;
            $normalizedRequired = $synonyms[$role] ?? $role;
            if ($normalizedUserRole === $normalizedRequired) {
                return $next($request);
            }
        }
        abort(403, 'Unauthorized');
    }

}
