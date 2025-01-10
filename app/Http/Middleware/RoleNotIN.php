<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;

class RoleNotIN
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (Auth::user()->hasRole($role)) {
            abort(403,'User does not have the right roles.');
        }
        return $next($request);

    }
}
