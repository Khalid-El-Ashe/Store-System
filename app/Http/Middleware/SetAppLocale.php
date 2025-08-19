<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetAppLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $locale = request('locale', Cookie::get('locale', config('app.locale'))); // this value was encrypted in the cookie

        // هان سيتم تخزين اللغة المتختارة لمدة يوم كامل 24 ساعة
        // Cookie::queue('locale', $locale, 60 * 24 * 30); // Store locale in cookie for 30 days

        $locale = $request->route('locale');

        App::setLocale($locale); //todo in here i need make facade method to change the Language app

        // in my route i need the locale attribute so i need to send this attribute from here in middleware
        URL::defaults(['locale' => $locale]);
        Route::current()->forgetParameter('locale'); // Clear the locale parameter from the current route

        return $next($request);
    }
}
