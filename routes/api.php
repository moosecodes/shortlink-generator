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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/shortlinks/create', [CreateLinkController::class, 'index']);
Route::post('/shortlinks/free', [CreateLinkController::class, 'freeLink']);

Route::get('/shortlinks/show/all', [ShowLinkController::class, 'showAll']);
Route::get('/shortlinks/show/active', [ShowLinkController::class, 'showActive']);
Route::get('/shortlinks/show/{id}', [ShowLinkController::class, 'index']);

Route::get('/shortlinks/redirect/{short_code}', [RedirectLinkController::class, 'index']);
Route::post('/shortlinks/redirect/urls', [RedirectLinkController::class, 'getUrls']);

Route::patch('/shortlink/update', [UpdateLinkController::class, 'index']);

Route::patch('/shortlinks/activate/{id}', [StatusController::class, 'activate']);
Route::patch('/shortlinks/deactivate/{id}', [StatusController::class, 'deactivate']);

Route::delete('/shortlinks/delete/{id}', [DeleteLinkController::class, 'index']);

Route::get('location/{ip}', [LocationController::class, 'index']);
