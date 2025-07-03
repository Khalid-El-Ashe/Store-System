<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Store extends Model
{
    use HasFactory, Notifiable;
    // protected $connection = 'mysql'; //
    // protected $table = 'categories';
    // protected $primaryKey = 'id'; // but this line is default found in Model class
    // public $incrementing = true; // this mean is the primaryKey is autoincrement
    // public $timestamps = true;

    // i have the relation store has many products
    public function products()
    {
        return $this->hasMany(Product::class, 'store_id', 'id');
    }
}
