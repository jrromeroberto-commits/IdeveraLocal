<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsUsable
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! $user->isRestricted() || $request->routeIs(
            'home', 'login', 'login.store', 'logout',
            'password.request', 'password.email', 'password.reset', 'password.update',
            'verification.notice', 'verification.verify', 'verification.send',
        )) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            abort(403, 'Esta cuenta no tiene acceso al área privada.');
        }

        return Inertia::render('auth/account-restricted', ['accountStatus' => $user->accountStatus()])
            ->toResponse($request)->setStatusCode(403);
    }
}
