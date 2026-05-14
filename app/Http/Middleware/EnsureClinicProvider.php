<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClinicProvider
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $user = auth()->user();

        // SUPER ADMIN CAN ACCESS
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // PROVIDER MUST BE CLINIC
        if (
            $user->provider &&
            $user->provider->type === 'clinic'
        ) {
            return $next($request);
        }

        abort(403, 'Unauthorized.');
    }
}