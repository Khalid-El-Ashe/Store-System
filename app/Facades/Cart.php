<?php

namespace App\Facades;

use App\Repositories\Cart\CartRepository;
use Illuminate\Support\Facades\Facade;

class Cart extends Facade
{
    // the job of this facade function jsut return the name of varable is saved in serviceContainer
    protected static function getFacadeAccessor()
    {
        return CartRepository::class; // this is my serviceContainer class
    }
}
