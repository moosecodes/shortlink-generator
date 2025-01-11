<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
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

    Route::get('/shortlinks/new', function () {
        return Inertia::render('CreateShortlink');
    });

    Route::get('/shortlinks/update/{id}', function ($shortlink_id) {
        return Inertia::render('UpdateShortlink', [
            'shortlink_id' => $shortlink_id,
        ]);
    });

    Route::get('/shortlinks/all', function () {
        return Inertia::render('ListAllShortlinks');
    });

    Route::get('/shortlinks/info/{shortlink_id}', function ($shortlink_id) {
        return Inertia::render('ShortlinkDetails', [
            'shortlink_id' => $shortlink_id,
        ]);
    });
});
