<?php

namespace App\Models;

use App\Observers\CartObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    public $incrementing = false; // this is to do not make the id is autoincrement

    protected $fillable = [
        'cookie_id',
        'user_id',
        'product_id',
        'quantity',
        'options',
    ];

    // Events (observes)
    // createing, created, updating, updated, saving, saved,
    // deleting, deleted, restoring, restored, retrieved, booted

    // i need to use the observer listener
    protected static function booted()
    {
        // parent::boot();
        // this function is inject into the model
        // when the model is created
        // i need to make the uuid
        // so i need to use the creating event
        // static::creating(function (Cart $cart) // this is observe function
        // {
        //     // in the first i need to make the uuid
        //     $cart->id = Str::uuid();
        // });
        static::observe(CartObserver::class); # this is to use the CartObserver class and to make uuid for the cart item

        // اريد ان اعمل هذه الدالة العامة لانها تتكرر في كثير من الكود بالتالي عملته لنظافة الكود Clean Coding
        static::addGlobalScope('cookie_id', function (Builder $builder) {
            $builder->where('cookie_id', '=', Cart::getCookieId());
        });
    }

    // i need to build my relashinships
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(['name'=> 'Anonymous']);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public static function  getCookieId(): string
    {
        // this function to get the cookie id
        // if the cookie id is not set, i will make a new one
        $cookieId = Cookie::get('cart_id');

        if (!$cookieId) {
            $cookieId = Str::uuid();
            // set the cookie for 30 days and i need to use the Carbon native php class to use DateTime
            Cookie::queue('cart_id', $cookieId, 60 * 24 * 30);
        }
        return $cookieId;
    }
}
