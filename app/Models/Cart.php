<?php

namespace App\Models;

use App\Observers\CartObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    public $incrementing = false;

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
    public static function boot()
    {
        parent::boot();

        // this function is inject into the model
        // when the model is created
        // i need to make the uuid
        // so i need to use the creating event
        // static::creating(function (Cart $cart) {
        //     // in the first i need to make the uuid
        //     $cart->id = Str::uuid();
        // });

        static::observe(CartObserver::class);
    }

    // i need to build my relashinships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault('name', 'Anonymous');
    }
}
