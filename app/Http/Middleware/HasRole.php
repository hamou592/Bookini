<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // If route only requires authenticated user with any role
        if (empty($roles)) {

            if (!$user->roles()->exists()) {
                return response()->view(
                    'errors.unauthorized',
                    [],
                    403
                );
            }

            return $next($request);
        }

        // CHECK MULTIPLE ROLES
        foreach ($roles as $role) {

            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        return response()->view(
            'errors.unauthorized',
            [],
            403
        );
    }
}