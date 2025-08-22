<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleAbility extends Model
{
    use HasFactory;

    //todo i remove the timestamps in the migration table so i need remove in here
    public $timestamps = false;

    protected $fillable = [
        'role_id',
        'ability',
        'type',
    ];
}
