<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShortlinkController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/shortlinks', [ShortlinkController::class, 'store']);
Route::patch('/shortlinks/{id}/deactivate', [ShortlinkController::class, 'deactivate']);
