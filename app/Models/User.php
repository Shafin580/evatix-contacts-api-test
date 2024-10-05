<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    // Granting Access to Columns to be filled when creating a new entry or updating an existing entry
    protected $fillable = [
        'name', 'email', 'password', 'activation_token', 'is_active',
    ];

    // When Fetching Data, These Columns will not show data
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Data Casting when fetching data
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Function To Return the unique identifier of this user 
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // this Method will return the relationship between user and contacts
    public function contacts()
    {
        return $this->hasMany(Contacts::class);
    }
}