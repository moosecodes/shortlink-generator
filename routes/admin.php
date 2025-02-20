<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DomainsController;

Route::prefix('admin')->name('admin.')->middleware(['auth:sanctum', 'admin'])->group(function () {
  Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
  Route::get('/custom-domains', [DomainsController::class, 'index'])->name('domains');
});
