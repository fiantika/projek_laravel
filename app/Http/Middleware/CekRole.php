<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   // app/Http/Middleware/CekRole.php
    public function handle($request, Closure $next, ...$roles)
    {
        /**
         * This middleware now supports checking against multiple roles. The list
         * of roles is passed as variadic parameters from the route definition
         * (e.g. `role:operator,keuangan`). If the authenticated user's role
         * matches any of the supplied roles, the request is allowed to
         * proceed. Otherwise an HTTP 403 response is returned.
         */
        if (auth()->check() && in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }
        abort(403, 'Unauthorized');
    }

}
