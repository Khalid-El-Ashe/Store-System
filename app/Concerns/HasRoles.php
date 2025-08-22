<?php
namespace App\Concerns;

use App\Models\Role;

//todo this is my trait to make user has roles and abilities

trait HasRoles {

    // we have a realation many to many with roles model is morph is with (Admin, User)
    public function roles()
    {
        return $this->morphToMany(Role::class, 'authorizable', 'role_user');
    }

    // i need make method to return or get the role_id
    public function hasAbility($ability)
    {

        // i need to using the roles relation by morph


        $denied = $this->roles()->whereHas('abilities', function ($query) use ($ability) {
            $query->where('ability', $ability)
                  ->where('type', '=', 'deny');
        })->exists();


        if ($denied) {
            return false;
        }

        return $this->roles()->whereHas('abilities', function ($query) use ($ability) {
            $query->where('ability', $ability)
                ->where('type', '=', 'allow');
        })->exists();
    }
}
