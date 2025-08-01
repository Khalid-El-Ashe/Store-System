<?php

use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Fortify;

// CeheckUserType this is class middleware is created by me to handle user type
// and redirect to login if user is not authenticated or
// , 'auth.type:admin,super-admin'

// مرحبا هان في موضوع الحارس تاع الرابط صار عندي مشكلة وهي تداخل انواع الحراس في الباك فا اضطررت اعمل متغير بجيب نوع الحارس من المكتبة وتوجيهه في الرابط حتى امنع كل نوع على حدى
$guard = config('fortify.guard');
// Route::prefix('dashboard')->middleware(["auth:{$guard}"])->group(function () {
Route::prefix('admin/dashboard')->middleware(['auth:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    // Route::resource('/', DashboardController::class)->only('index');
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

// Route::prefix('dashboard')->middleware('auth:web')->group(function () {
//     Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
// });
