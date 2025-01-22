<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\CheckShortlinkExpiration;
use App\Http\Controllers\Api\RedirectLinkController;

Route::get('/', function () {
    return Inertia::render('LandingPage', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/shortlink/new', function () {
        return Inertia::render('NewShortlink');
    })->name('newShortlink');

    Route::get('/shortlink/update/{shortlink_id}', function ($shortlink_id) {
        return Inertia::render('UpdateShortlink', [
            'shortlink_id' => $shortlink_id,
        ]);
    });

    Route::get('/manage/shortlinks', function () {
        return Inertia::render('ManageShortlinks');
    })->name('showAllShortlinks');
});

Route::get('/{short_code}', [RedirectLinkController::class, 'index'])
    ->middleware(CheckShortlinkExpiration::class)
    ->name('shortlink.redirect');
