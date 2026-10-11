<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        return to_route($request->user()->role->dashboardRoute());
    }

    public function show(Request $request): Response
    {
        return Inertia::render('dashboard', ['role' => $request->user()->role->value]);
    }
}
