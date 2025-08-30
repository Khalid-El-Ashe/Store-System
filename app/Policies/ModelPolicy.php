<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Str;

class ModelPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    // i need make the before method to filter the user type
    // public function before($user, $ability)
    // {
    //     if ($user->super_admin) {
    //         return true; // Grant all permissions to super admin
    //     }
    // }

    public function __call($method, $args)
    {
        $class_name = str_replace('Policy', '', class_basename($this));
        $class_name = Str::plural(Str::lower($class_name));

        if ($method == 'viewAny') {
            $method = 'view';
        }

        $ability = $class_name . '.' . Str::kebab($method);
        $user = $args[0];

        if (isset($args[1])) {
            $model = $args[1];
            if ($model->store_id !== $user->store_id) {
                return false;
            }
        }

        return $user->hasAbility($ability);
    }
}
