<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\CheckShortlinkExpiration;
use App\Http\Controllers\Api\LinkRedirectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinkAnalyticsController;
use App\Models\Metadata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BillingController;

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
    Route::post('/checkout', [BillingController::class, 'checkout'])->name('checkout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/links/manage', function (Request $request) {
        return Inertia::render('LinksManagePage', [
            'title' => 'Manage Links',
            'links' => Auth::user()->shortlinks,
            'metas'  => Metadata::where('user_id', $request->user()->id),
        ]);
    })->name('manage.links');

    Route::prefix('link')->name('link.')->group(function () {
        Route::get('/analytics/{shortlink_id}', [LinkAnalyticsController::class, 'index'])->name('analytics');

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

Route::get('/pricing', function () {
    return Inertia::render('PricingPage');
})->name('pricing');

Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/{short_code}', [LinkRedirectController::class, 'index'])
        ->middleware(CheckShortlinkExpiration::class)
        ->name('shortlink.redirect');
});
