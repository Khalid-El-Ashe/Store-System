<?php

use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Front\Auth\TwoFactorAuthenticationContrller;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutContrller;
use App\Http\Controllers\Front\CurrencyConverterController;
use App\Http\Controllers\Front\HomeContrller;
use App\Http\Controllers\Front\ProductsController;
use App\Http\Controllers\SocialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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


// Route::prefix('/')->group(function () {
//     Route::resource('dashboard', DashboardController::class);
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dash', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// todo LaravelLocalization::setLocale() -> this is from mcamara package
Route::group(['prefix' => LaravelLocalization::setLocale()], function () {

    Route::get('/', [HomeContrller::class, 'index'])->name('home');

    Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('/products/{product:slug}', [ProductsController::class, 'show'])->name('products.show');
    Route::get('/products/search', [ProductsController::class, 'search'])->name('products.search');
    Route::get('/products/filter', [ProductsController::class, 'filter'])->name('products.filter');

    // Route::middleware('auth')->group(function () {
    // Route::resource('/profile', ProfileController::class);
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // });

    Route::resource('cart', CartController::class);
    Route::get('checkout', [CheckoutContrller::class, 'create'])->name('checkout');
    Route::post('checkout', [CheckoutContrller::class, 'store']);

    Route::get('auth/user/2fa', [TwoFactorAuthenticationContrller::class, 'index'])->middleware('auth')->name('front.2FA');

    Route::post('/paypal/webhook', function () {
        echo 'Webhook received';
    })->name('paypal.webhook');

    // Route::get('/currency', function () {
    //   echo 'Currency page';
    // });
    // Route::post('/currency-store', [CurrencyConverterController::class, 'store'])->name('currency.store');
    Route::get('/currency', function () {
        return view('layouts.front');
    });
    Route::post('/currency-store', [CurrencyConverterController::class, 'store'])->name('currency.store');
});


//todo this routes for Socialite with Social media accounts
Route::get('auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('auth.socialite.redirect');
Route::get('auth/{provider}/callback', [SocialLoginController::class, 'callback'])->name('auth.socialite.callback');

Route::get('auth/{provider}/user', [SocialController::class, 'index'])->name('auth.social.user');

Route::get('wellcom', function () {
    $username = Auth::user()->name;
    echo 'wellocm' . $username;
});

// i need to implement the routes class
// require __DIR__ . '/auth.php';
require __DIR__ . '/dashboard.php';
// include('dashboard.php');
