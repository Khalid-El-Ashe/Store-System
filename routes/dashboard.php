<?php

use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProfileController;
use Illuminate\Support\Facades\Route;


Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('/', DashboardController::class);
    // soft deletes
    Route::get('categories/trashed', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::put('categories/{category}', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{category}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');
    //
    Route::resource('/categories', CategoryController::class);
    Route::resource('/products', ProductController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
