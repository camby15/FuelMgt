<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'profile_name',
        'description',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'profile_id');
    }

    public function subUsers()
    {
        return $this->hasMany(CompanySubUser::class, 'profile_id');
    }

    public function menuAccess()
    {
        return $this->hasMany(ProfileMenuAccess::class, 'profile_id');
    }
}
