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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        // 'App\Models\Role' => 'App\Policies\RolePolicy',
        // 'App\Models\Product' => 'App\Policies\ProductPolicy',
    ];

    public function register()
    {
        parent::register();
        $this->app->bind('abilities', function () {
            return include base_path('data/abilities.php');
        });
    }

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // // if the user is super admin i need to give him all permissions and abilities and show all the nav items
        // Gate::before(function ($user) {
        //     if ($user->super_admin) {
        //         return true; // Grant all permissions to super admin
        //     }
        // });

        // $abilities = include base_path('data/abilities.php');
        foreach ($this->app->make('abilities') as $code => $lable) {
            Gate::define($code, function ($user) use ($code) {
                // Check if the user has the ability/permission or not
                return $user->hasAbility($code);
            });
        }
    }
}
