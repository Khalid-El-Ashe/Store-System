<?php

namespace App\Providers;

use App\Rules\Filter;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('filter', function ($attribute, $value, $params) {
            return ! in_array(strtolower($value), $params);
        }, 'you can not used this value');

        Paginator::useBootstrapFive();
    }
}
