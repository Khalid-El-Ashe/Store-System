<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Intl\Countries;

class OrderAddress extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'type',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'street_address',
        'city',
        'postal_code',
        'state',
        'country'
    ];

    // i need to make some accessoure function to
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    public function getCountryNameAttribute()
    {
        Countries::getName($this->country);
    }
}
