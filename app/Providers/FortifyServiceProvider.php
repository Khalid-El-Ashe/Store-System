<?php

namespace App\Providers;

use App\Actions\Fortify\AuthenticateUser;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */


    public function register(): void
    {
        // authintication by multi Guards هيك فعليا بنصير نستخدم نفس الواجهات ونفس المسارات لأكثر من نوع
        $request = request();
        if ($request->is('admin/*')) {
            Config::set('fortify.guard', 'admin');
            Config::set('fortify.passwords', 'admins');
            Config::set('fortify.prefix', 'admin');
            Config::set('fortify.home', 'admin/dashboard');
        }

        $guard = Config::get('fortify.guard');

        if ($guard == 'admin') {
            Fortify::authenticateUsing([new AuthenticateUser, 'authenticate']); //todo AuthenticateUser i build this class to cut the logic authenticatio (callback method)
            Fortify::viewPrefix('auth.');
        } else {
            Fortify::viewPrefix('front.auth.');
        }

        $this->app->instance(LoginResponse::class, new class implements LoginResponse { // anonynemose class
            public function toResponse($request)
            {
                if($request->user(guard: 'admin')) {
                    return redirect()->intended('admin/dashboard');
                }
                return redirect()->intended('/');
            }
        });

        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse { // anonynemose class
            public function toResponse($request)
            {
                if($request->user('admin')) {
                    return redirect()->intended('admin/login');
                }
                return redirect()->intended('login');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });


        // authintication by multi Guards هيك فعليا بنصير نستخدم نفس الواجهات ونفس المسارات لأكثر من نوع
        // app()->booted(function () {
        // $request = request();
        // if ($request->is('admin/*')) {
        //     Config::set('fortify.guard', 'admin');
        //     Config::set('fortify.passwords', 'admins');
        //     Config::set('fortify.prefix', 'admin');
        //     }

        // $guard = Config::get('fortify.guard');

        // if ($guard == 'admin') {
        //     Fortify::authenticateUsing([new AuthenticateUser, 'authenticate']); //todo AuthenticateUser i build this class to cut the logic authenticatio (callback method)
        //     Fortify::viewPrefix('auth.');
        // } else {
        //     Fortify::viewPrefix('front.auth.');
        // }
        // });


        ############################################################################################################################################

        // Fortify::viewPrefix('auth.'); // if you have a default name for your views, you can set it here


        // and you can to make check the guard and direct the route is
        // Fortify::loginView(function () {
        //     $guard = Config('fortify.guard');
        //     if($guard == 'web') {
        //         return view('front.auth.login');
        //     }
        //     // else
        //     return view('auth.login');
        // });

        // // another way
        // $guard = Config('fortify.guard');
        // if($guard == 'admin') {
        // // i need make Custome Authentications login by username or email or phonenumber
        // Fortify::authenticateUsing([new AuthenticateUser, 'authenticate']); //todo AuthenticateUser i build this class to cut the logic authenticatio (callback method)
        // Fortify::viewPrefix('auth.');
        // } else {
        //     Fortify::viewPrefix('front.auth.');
        // }

        // i need to add the views for Fortify
        // Fortify::loginView(function () {
        //     return view('auth.login');
        // });
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });
        Fortify::registerView(function () {
            return view('auth.register');
        });
        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });
        Fortify::twoFactorChallengeView(function () {
            return view('front.auth.two-factor-challenge');
        });
    }
}
