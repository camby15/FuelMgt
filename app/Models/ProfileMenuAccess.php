<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileMenuAccess extends Model
{
    use HasFactory;

    protected $table = 'profile_menu_access';

    protected $fillable = [
        'profile_id',
        'menu_key',
        'menu_name',
        'menu_icon',
        'menu_route',
        'parent_menu',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function profile()
    {
        return $this->belongsTo(UserProfile::class);
    }
}
