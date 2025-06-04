<?php

use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;


Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('/', DashboardController::class);
    Route::resource('/categories', CategoryController::class);
});
