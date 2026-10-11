<?php

use App\Http\Middleware\EnsureAccountIsUsable;
use App\Http\Middleware\EnsureOwnerTwoFactor;
use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);
        $middleware->alias(['role' => EnsureRole::class, 'owner.two-factor' => EnsureOwnerTwoFactor::class]);
        // Authorize before resolving account IDs, preserving verification and the restriction page.
        foreach ([HandleInertiaRequests::class, EnsureAccountIsUsable::class, EnsureEmailIsVerified::class, EnsureRole::class, EnsureOwnerTwoFactor::class] as $accountMiddleware) {
            $middleware->prependToPriorityList(SubstituteBindings::class, $accountMiddleware);
        }

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            EnsureAccountIsUsable::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
