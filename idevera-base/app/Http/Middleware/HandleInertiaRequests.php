<?php

namespace App\Http\Middleware;

use App\Models\ContentEntry;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'site' => fn () => SiteSetting::publicData(),
            'publicPageSlugs' => fn () => ContentEntry::query()->published()->where('kind', 'PAGE')->pluck('published_slug'),
            'canonical' => rtrim((string) config('app.url'), '/').$request->getPathInfo(),
            'origin' => rtrim((string) config('app.url'), '/'),
            'preferredServiceSlug' => function () use ($request) {
                $slug = $request->session()->get('preferred_service_slug');

                return is_string($slug) && ContentEntry::query()->published()->where('kind', 'SERVICE')->where('published_slug', $slug)->exists() ? $slug : null;
            },
            'auth' => [
                'user' => $request->user() ? [
                    ...$request->user()->only(['id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at']),
                    'role' => $request->user()->role->value,
                    'account_status' => $request->user()->accountStatus(),
                    'two_factor_enabled' => $request->user()->hasEnabledTwoFactorAuthentication(),
                ] : null,
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
