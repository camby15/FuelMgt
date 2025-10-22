<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\User;

class CompanyProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', // Foreign key linking to the users table
        'company_name', // Name of the company
        'company_email', // Official company email
        'company_phone', // Company contact number
        'primary_email', // Primary contact email for the company
        'pin_code', // Encrypted PIN for company authentication
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pin_code', // Hide the PIN when serializing models
    ];

    /**
     * Define a reverse one-to-one relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the customers for the company profile.
     */
    public function customers()
    {
        return $this->hasMany(Customer::class, 'company_id');
    }
}
