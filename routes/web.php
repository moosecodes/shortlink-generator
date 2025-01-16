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
        return Inertia::render('NewShortlink');
    })->name('newShortlink');

    Route::get('/shortlinks/edit/{shortlink_id}', function ($shortlink_id) {
        return Inertia::render('EditShortlink', [
            'shortlink_id' => $shortlink_id,
        ]);
    });

    Route::get('/shortlinks/show/all', function () {
        return Inertia::render('ViewAllShortlinks');
    })->name('showAllShortlinks');

    Route::get('/shortlinks/redirect/{id}', function ($id) {
        return redirect(url('api/shortlinks/redirect/' . $id));
    });
});
