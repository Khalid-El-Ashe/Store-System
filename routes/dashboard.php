<?php

use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Route;


// CeheckUserType this is class middleware is created by me to handle user type
// and redirect to login if user is not authenticated or
Route::prefix('dashboard')->middleware(['auth', 'auth.type:admin,super-admin'])->group(function () {
    Route::resource('/', DashboardController::class);
    // soft deletes
    Route::get('categories/trashed', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::put('categories/{category}', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{category}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');
    //
    Route::resource('/categories', CategoryController::class);
    Route::resource('/product', ProductController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
