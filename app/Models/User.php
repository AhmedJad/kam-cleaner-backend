<?php

namespace App\Models;

use App\Commons\Consts\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail, CanResetPassword

{
    use  Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'type',
        'password_confirmation',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes["password"] = Hash::make($value);
    }
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        $claims = [
            "email" => $this->attributes["email"],
            "first_name" => $this->attributes["first_name"],
            "last_name" => $this->attributes["last_name"],
            "email_verified_at" => $this->attributes["email_verified_at"],
        ];
        if ($this->attributes["type"]) {
            $claims["type"] = Role::ADMIN;
        }
        return $claims;
    }
}
