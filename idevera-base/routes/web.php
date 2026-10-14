<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Owner\AccountController;
use App\Http\Controllers\Owner\ContactController;
use App\Http\Controllers\Owner\ContentController;
use App\Http\Controllers\PublicContentController;
use App\Http\Controllers\StudioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [StudioController::class, 'home'])->name('home');
Route::get('design/project', [StudioController::class, 'projectPreview'])->name('design.project');

Route::name('public.')->group(function () {
    Route::get('servicios', [PublicContentController::class, 'services'])->name('services');
    Route::get('servicios/{slug}', [PublicContentController::class, 'service'])->name('service');
    Route::get('portfolio', [PublicContentController::class, 'portfolio'])->name('portfolio');
    Route::get('portfolio/{slug}', [PublicContentController::class, 'caseStudy'])->name('case');
    Route::get('about', [PublicContentController::class, 'about'])->name('about');
    Route::get('proceso', [PublicContentController::class, 'process'])->name('process');
    Route::get('legal/{slug}', [PublicContentController::class, 'legal'])->name('legal');
    Route::get('paginas/{slug}', [PublicContentController::class, 'page'])->name('page');
    Route::get('contacto', [PublicContentController::class, 'contact'])->name('contact');
    Route::post('contacto', [PublicContentController::class, 'sendContact'])->middleware('throttle:contact')->name('contact.store');
    Route::get('iniciar-proyecto', [PublicContentController::class, 'start'])->name('start');
    Route::get('media/{entry}/{token}', [PublicContentController::class, 'media'])->whereUuid('token')->name('media');
    Route::get('sitemap.xml', [PublicContentController::class, 'sitemap'])->name('sitemap');
    Route::get('robots.txt', [PublicContentController::class, 'robots'])->name('robots');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'redirect'])->name('dashboard');
    Route::get('client', [DashboardController::class, 'show'])->middleware('role:CLIENT')->name('client.dashboard');
    Route::get('staff', [DashboardController::class, 'show'])->middleware('role:STAFF')->name('staff.dashboard');

    Route::prefix('owner')->name('owner.')->middleware(['role:OWNER', 'owner.two-factor'])->group(function () {
        Route::get('/', [DashboardController::class, 'show'])->name('dashboard');
        Route::get('accounts', [AccountController::class, 'index'])->name('accounts.index');
        Route::prefix('content')->name('content.')->controller(ContentController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::get('settings', 'settings')->name('settings');
            Route::post('settings', 'updateSettings')->middleware('password.confirm:password.confirm,900')->name('settings.update');
            Route::post('/', 'store')->middleware('throttle:30,1')->name('store');
            Route::get('{entry}/edit', 'edit')->name('edit');
            Route::post('{entry}', 'update')->middleware('throttle:30,1')->name('update');
            Route::post('{entry}/publication', 'publish')->middleware(['password.confirm:password.confirm,900', 'throttle:20,1'])->name('publish');
            Route::get('{entry}/media/{media}', 'media')->name('media');
        });
        Route::get('contact', [ContactController::class, 'index'])->name('contact.index');
        Route::post('contact/{message}/read', [ContactController::class, 'read'])->name('contact.read');
        Route::middleware('password.confirm:password.confirm,900')->group(function () {
            Route::post('accounts', [AccountController::class, 'store'])->middleware('throttle:10,1')->name('accounts.store');
            Route::patch('accounts/{user}', [AccountController::class, 'update'])->middleware('throttle:30,1')->name('accounts.update');
        });
    });
});

require __DIR__.'/settings.php';

Route::fallback(function (Request $request) {
    abort_if($request->expectsJson(), 404);

    return Inertia::render('public/error', ['status' => 404])->toResponse($request)->setStatusCode(404);
})->name('public.not-found');
