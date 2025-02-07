<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\CheckShortlinkExpiration;
use App\Http\Controllers\Api\RedirectLinkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinkGraphController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
    \App\Http\Middleware\AutoLoginGuest::class,
    \App\Http\Middleware\LogAuthStatus::class,
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/links/manage', function () {
        return Inertia::render('LinksManagePage', [
            'title' => 'Manage Links',
            'links' => Auth::user()->shortlinks,
        ]);
    })->name('show.links');

    Route::prefix('link')->name('link.')->group(function () {
        Route::get('/analytics/{shortlink_id}', [LinkGraphController::class, 'index'])->name('analytics');

        Route::get('/new', function () {
            return Inertia::render('LinkCreatePage', [
                'title' => 'Create New Link'
            ]);
        })->name('create');

        Route::get('/edit/byShortCode/{linkShortCode}', function ($linkShortCode) {
            return Inertia::render('LinkEditPage', [
                'linkShortCode' => $linkShortCode,
                'title' => 'Edit Link: ' . $linkShortCode,
            ]);
        })->name('update');
    });

    Route::get('/qrcodes', function () {
        return Inertia::render('LinksManagePage');
    })->name('qr-codes');

    Route::get('/pages', function () {
        return Inertia::render('LinksManagePage');
    })->name('pages');

    Route::get('/analytics', function () {
        return Inertia::render('LinksManagePage');
    })->name('page.analytics');

    Route::get('/campaigns', function () {
        return Inertia::render('LinksManagePage');
    })->name('campaigns');

    Route::get('/custom-domains', function () {
        return Inertia::render('LinksManagePage');
    })->name('custom-domains');

    Route::get('/settings', function () {
        return Inertia::render('LinksManagePage');
    })->name('settings');
});

Route::get('/{short_code}', [RedirectLinkController::class, 'index'])
    ->middleware(CheckShortlinkExpiration::class)
    ->name('shortlink.redirect');
