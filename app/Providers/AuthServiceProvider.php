<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\abilities;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    public function register() {
        parent::register();
        $this->app->bind('abilities',function() {
            return include base_path('data/abilities.php');
        });
    }

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // $abilities = include base_path('data/abilities.php');
        foreach($this->app->make('abilities') as $code => $lable) {
            Gate::define($code, function ($user) use ($code) {
                // Check if the user has the ability/permission or not
                return $user->hasAbility($code);
            });
        }
    }
}
