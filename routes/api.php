<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CreateLinkController;
use App\Http\Controllers\Api\DeleteLinkController;
use App\Http\Controllers\Api\RedirectLinkController;
use App\Http\Controllers\Api\ShowLinkController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\UpdateLinkController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Middleware\CheckShortlinkExpiration;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Define the redirect route first to give it priority
Route::get('/{short_code}', [RedirectLinkController::class, 'index'])
    ->middleware(CheckShortlinkExpiration::class)
    ->name('shortlink.redirect');

// Other API routes
Route::post('/shortlinks/create', [CreateLinkController::class, 'index']);
Route::post('/shortlinks/free', [CreateLinkController::class, 'freeLink']);

Route::post('/shortlinks/show/all', [ShowLinkController::class, 'showAll']);
Route::post('/shortlinks/show/active', [ShowLinkController::class, 'showActive']);
Route::post('/shortlinks/show', [ShowLinkController::class, 'index']);

Route::post('/urls', [RedirectLinkController::class, 'getUrls']);

Route::patch('/shortlink/update', [UpdateLinkController::class, 'index']);

Route::patch('/shortlinks/activate/{id}', [StatusController::class, 'activate']);
Route::patch('/shortlinks/deactivate/{id}', [StatusController::class, 'deactivate']);

Route::delete('/shortlinks/delete/{id}', [DeleteLinkController::class, 'index']);

Route::get('location/{ip}', [LocationController::class, 'index']);
