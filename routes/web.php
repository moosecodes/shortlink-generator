<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\CheckShortlinkExpiration;
use App\Http\Controllers\Api\RedirectLinkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinkGraphController;
use Carbon\Carbon;

Route::get('/', function () {
    return Inertia::render('LandingPage', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('landingPage');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/link/graphs/{shortlink_id}', [LinkGraphController::class, 'index'])->name('link.analytics');

    Route::get('/link/new', function () {
        return Inertia::render('NewLinkPage');
    })->name('NewLinkPage');

    Route::get('/link/update/{shortlink_id}', function ($shortlink_id) {
        return Inertia::render('UpdateShortlink', [
            'shortlink_id' => $shortlink_id,
        ]);
    });

    Route::get('/links/manage', function () {
        return Inertia::render('ManageLinksPage');
    })->name('show.links');

    Route::get('/qrcodes', function () {
        return 'qrcodes';
    })->name('qr-codes');
    Route::get('/analytics', function () {
        return 'analytics';
    })->name('analytics');
    Route::get('/custom-domains', function () {
        return 'custom-domains';
    })->name('custom-domains');
    Route::get('/settings', function () {
        return 'settings';
    })->name('settings');
});

Route::get('/{short_code}', [RedirectLinkController::class, 'index'])
    ->middleware(CheckShortlinkExpiration::class)
    ->name('shortlink.redirect');
