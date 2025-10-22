<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname', // Individual's full name
        'personal_email', // Individual's email
        'phone_number', // Individual's phone number
        'pin_code', // Encrypted PIN for authentication
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pin_code', // Hide the PIN when serializing models
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Define a one-to-one relationship with the CompanyProfile model.
     */
    public function companyProfile()
    {
        return $this->hasOne(CompanyProfile::class);
    }
}
