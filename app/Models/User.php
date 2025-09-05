<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Concerns\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use
        HasApiTokens,
        HasFactory,
        Notifiable,
        TwoFactorAuthenticatable,
        HasRoles; // (HasRoles) this is my trait class created by me;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'provider_token'
    ];

    //todo here you need add some hidden importance Data you do not need the use to show this data
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'provider_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        // 'provider_token' => 'encrypted'
    ];

    // i need to make relation one to one with Profile model
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id')->withDefault(); // if i do not have an profile i need to return the defaultValue
    }

    public function setProviderTokenAttribute($value)
    {
        //todo i need encription the provider_token becuase is have private Data
        $this->attributes['provider_token'] = Crypt::encryptString($value);
    }

    public function getProviderTokenAttribute($value)
    {
        // dump(Crypt::decryptString($value));
        return Crypt::decryptString($value);
    }
}
