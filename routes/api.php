<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LinkCreateController;
use App\Http\Controllers\Api\LinkDeleteController;
use App\Http\Controllers\Api\LinkRedirectController;
use App\Http\Controllers\Api\LinkShowController;
use App\Http\Controllers\Api\LinkStatusController;
use App\Http\Controllers\Api\LinkUpdateController;
use App\Http\Middleware\CheckShortlinkExpiration;
use App\Http\Controllers\Api\LinkClicksOverTime;

// Secure all routes except the last one
Route::middleware('auth:sanctum', \App\Http\Middleware\AutoLoginGuest::class, \App\Http\Middleware\LogAuthStatus::class)->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/clicks/overtime/{id}', [LinkClicksOverTime::class, 'index']);
    Route::post('/shortlinks/free', [LinkCreateController::class, 'index']);
    Route::post('/manage/new', [LinkCreateController::class, 'index']);
    Route::get('/links/manage', [LinkShowController::class, 'showAll']);
    Route::post('/link/details', [LinkShowController::class, 'index']);
    Route::patch('/link/edit/byShortCode/', [LinkUpdateController::class, 'update']);
    Route::patch('/link/activate/{id}', [LinkStatusController::class, 'activate']);
    Route::patch('/link/deactivate/{id}', [LinkStatusController::class, 'deactivate']);
    Route::delete('/link/delete/{id}', [LinkDeleteController::class, 'index']);
});

// Public route (no authentication required)
Route::get('/{short_code}', [LinkRedirectController::class, 'index'])
    ->middleware(CheckShortlinkExpiration::class)
    ->name('api.shortlink.redirect');
