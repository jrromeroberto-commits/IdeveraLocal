<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class EnsureOwnerTwoFactor
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->hasEnabledTwoFactorAuthentication()) {
            if ($request->expectsJson()) {
                abort(423, 'Activa y confirma la autenticación de dos factores para administrar IDEVERA.');
            }

            Inertia::flash('toast', ['type' => 'error', 'message' => 'Activa y confirma la autenticación de dos factores para administrar IDEVERA.']);

            return to_route('security.edit');
        }

        return $next($request);
    }
}
