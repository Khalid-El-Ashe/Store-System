<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy extends ModelPolicy // todo i commented this line to use the __call magic method in the parent class (ModelPolicy)
{

    // /**
    //  *  if you work into the multi Guard do not need to set the guard model into the parameter just add the attreibute into the user model like ($user) not (User $user)
    //  * but if this Roles is just for one guard you need to set the model into the parameter like (User $user)
    //  */

    // // this functoin is runing before any other function in this class
    // public function before($user, $ability)
    // {
    //     if ($user->super_admin) {
    //         return true; // Grant all permissions to super admin
    //     }
    //     // if the user is not super admin i need to check the other functions in this class
    //     return null; // continue to other methodsX
    // }

    // /**
    //  * Determine whether the user can view any models.
    //  */
    // public function viewAny($user): bool
    // {
    //     return $user->hasAbility('products.view');
    // }

    // /**
    //  * Determine whether the user can view the model.
    //  */
    public function view($user, Product $product): bool
    {
        return $user->hasAbility('products.view'); // && $product->store_id == $user->store_id; // i need to check if the user is the owner of the product
    }

    // /**
    //  * Determine whether the user can create models.
    //  */
    // public function create($user): bool
    // {
    //     return $user->hasAbility('products.create');
    // }

    // /**
    //  * Determine whether the user can update the model.
    //  */
    // public function update($user, Product $product): bool
    // {
    //     // in here the user is can update just the products is have in him
    //     return $user->hasAbility('products.update') && $product->store_id == $user->store_id; // i need to check if the user is the owner of the product
    // }

    // /**
    //  * Determine whether the user can delete the model.
    //  */
    // public function delete($user, Product $product): bool
    // {
    //     // in here the user is can delete just the products is have in him
    //     return $user->hasAbility('products.delete') && $product->store_id == $user->store_id; // i need to check if the user is the owner of the product
    // }

    // /**
    //  * Determine whether the user can restore the model.
    //  */
    // public function restore($user, Product $product): bool
    // {
    //     return $user->hasAbility('products.restore');
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete($user, Product $product): bool
    // {
    //     return $user->hasAbility('products.foreceDelete');
    // }
}
