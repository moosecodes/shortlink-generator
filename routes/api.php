<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CreateLinkController;
use App\Http\Controllers\Api\DeleteLinkController;
use App\Http\Controllers\Api\RedirectLinkController;
use App\Http\Controllers\Api\ShowLinkController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\UpdateLinkController;
use App\Http\Middleware\CheckShortlinkExpiration;
use App\Http\Controllers\Api\GetClicksOverTime;

// Secure all routes except the last one
Route::middleware('auth:sanctum', \App\Http\Middleware\AutoLoginGuest::class, \App\Http\Middleware\LogAuthStatus::class)->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/clicks/overtime/{id}', [GetClicksOverTime::class, 'index']);
    Route::post('/shortlinks/free', [CreateLinkController::class, 'index']);
    Route::post('/manage/new', [CreateLinkController::class, 'index']);
    Route::get('/links/manage', [ShowLinkController::class, 'showAll']);
    Route::post('/link/details', [ShowLinkController::class, 'index']);
    Route::patch('/link/edit/byShortCode/', [UpdateLinkController::class, 'update']);
    Route::patch('/link/activate/{id}', [StatusController::class, 'activate']);
    Route::patch('/link/deactivate/{id}', [StatusController::class, 'deactivate']);
    Route::delete('/link/delete/{id}', [DeleteLinkController::class, 'index']);
    // Route::post('/redirects', [RedirectLinkController::class, 'getRedirects']);
});

// Public route (no authentication required)
Route::get('/{short_code}', [RedirectLinkController::class, 'index'])
    ->middleware(CheckShortlinkExpiration::class)
    ->name('api.shortlink.redirect');
