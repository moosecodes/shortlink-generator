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

    Route::prefix('link')->name('link.')->group(function () {
        Route::get('/graphs/{shortlink_id}', [LinkGraphController::class, 'index'])->name('analytics');

        Route::get('/new', function () {
            return Inertia::render('link.create');
        })->name('create');

        Route::get('/update/{shortlink_id}', function ($shortlink_id) {
            return Inertia::render('UpdateShortlink', [
                'shortlink_id' => $shortlink_id,
            ]);
        })->name('update');
    });

    Route::get('/links/manage', function () {
        return Inertia::render('ManageLinksPage');
    })->name('show.links');

    Route::get('/qrcodes', function () {
        return 'qrcodes page';
    })->name('qr-codes');
    Route::get('/analytics', function () {
        return 'analytics page';
    })->name('page.analytics');
    Route::get('/custom-domains', function () {
        return 'custom-domains page';
    })->name('custom-domains');
    Route::get('/settings', function () {
        return 'settings page';
    })->name('settings');
});

Route::get('/{short_code}', [RedirectLinkController::class, 'index'])
    ->middleware(CheckShortlinkExpiration::class)
    ->name('shortlink.redirect');
