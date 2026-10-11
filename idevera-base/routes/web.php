<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Owner\AccountController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'redirect'])->name('dashboard');
    Route::get('client', [DashboardController::class, 'show'])->middleware('role:CLIENT')->name('client.dashboard');
    Route::get('staff', [DashboardController::class, 'show'])->middleware('role:STAFF')->name('staff.dashboard');

    Route::prefix('owner')->name('owner.')->middleware(['role:OWNER', 'owner.two-factor'])->group(function () {
        Route::get('/', [DashboardController::class, 'show'])->name('dashboard');
        Route::get('accounts', [AccountController::class, 'index'])->name('accounts.index');
        Route::middleware('password.confirm:password.confirm,900')->group(function () {
            Route::post('accounts', [AccountController::class, 'store'])->middleware('throttle:10,1')->name('accounts.store');
            Route::patch('accounts/{user}', [AccountController::class, 'update'])->middleware('throttle:30,1')->name('accounts.update');
        });
    });
});

require __DIR__.'/settings.php';
