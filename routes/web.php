<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutContrller;
use App\Http\Controllers\Front\HomeContrller;
use App\Http\Controllers\Front\ProductsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeContrller::class, 'index'])->name('home');

// Route::prefix('/')->group(function () {
//     Route::resource('dashboard', DashboardController::class);
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dash', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductsController::class, 'show'])->name('products.show');
Route::get('/products/search', [ProductsController::class, 'search'])->name('products.search');
Route::get('/products/filter', [ProductsController::class, 'filter'])->name('products.filter');

Route::middleware('auth')->group(function () {
    // Route::resource('/profile', ProfileController::class);
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('cart', CartController::class);
Route::get('checkout', [CheckoutContrller::class, 'create'])->name('checkout');
Route::post('checkout', [CheckoutContrller::class, 'store']);


Route::post('/paypal/webhook', function () {
    echo 'Webhook received';
});

// i need to implement the routes class
require __DIR__ . '/auth.php';
require __DIR__ . '/dashboard.php';
// include('dashboard.php');
