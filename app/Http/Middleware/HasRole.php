<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasRole
{
    public function handle(Request $request, Closure $next, $role = null)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        // If specific role required
        if ($role && !auth()->user()->hasRole($role)) {
            return response()->view('errors.unauthorized', [], 403);
        }

        // If just "has any role"
        if (!$role && !auth()->user()->roles()->exists()) {
            return redirect('/login');
        }

        return $next($request);
    }
}