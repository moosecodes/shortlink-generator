<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShortlinkController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/shortlinks', [ShortlinkController::class, 'index']);
Route::get('/shortlinks/{id}', [ShortlinkController::class, 'show']);
Route::get('/shortlinks/active', [ShortlinkController::class, 'showActive']);
Route::get('/shortlinks/redirect/{short_code}', [ShortlinkController::class, 'redirect']);

Route::post('/shortlinks', [ShortlinkController::class, 'store']);

Route::patch('/shortlinks/update', [ShortlinkController::class, 'update']);
Route::patch('/shortlinks/{id}/deactivate', [ShortlinkController::class, 'deactivate']);
Route::patch('/shortlinks/{id}/activate', [ShortlinkController::class, 'activate']);

Route::delete('/shortlinks/{id}', [ShortlinkController::class, 'delete']);
