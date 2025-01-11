<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShortlinkController;
use App\Http\Controllers\Api\ShortlinkStatusController;
use App\Http\Controllers\Api\ShortlinkRedirectController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/shortlinks', [ShortlinkController::class, 'create']);

Route::get('/shortlinks/show/all', [ShortlinkController::class, 'showAll']);
Route::get('/shortlinks/show/active', [ShortlinkController::class, 'showActive']);
Route::get('/shortlinks/show/{id}', [ShortlinkController::class, 'show']);
Route::get('/shortlinks/redirect/{short_code}', [ShortlinkRedirectController::class, 'index']);

Route::patch('/shortlinks/update', [ShortlinkController::class, 'update']);

Route::patch('/shortlinks/activate/{id}', [ShortlinkStatusController::class, 'activate']);
Route::patch('/shortlinks/deactivate/{id}', [ShortlinkStatusController::class, 'deactivate']);

Route::delete('/shortlinks/delete/{id}', [ShortlinkController::class, 'delete']);
