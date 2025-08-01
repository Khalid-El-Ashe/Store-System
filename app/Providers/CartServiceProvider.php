<?php

namespace App\Providers;

use App\Repositories\Cart\CartModelRepository;
use App\Repositories\Cart\CartRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

#todo: you need to add this service provider to the config/app.php file in the providers array
class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            CartRepository::class,
            function () {
                return new CartModelRepository();
            }
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
