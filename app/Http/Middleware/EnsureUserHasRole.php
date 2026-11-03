<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            abort(401);
        }

        $userRoles = array_filter([$request->user()->role]);

        if ($roles === [] || empty(array_intersect($userRoles, $roles))) {
            abort(403);
        }

        return $next($request);
    }
}
