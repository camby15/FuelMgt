<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'company_id',
        'status'
    ];

    /**
     * Get the company that owns the user category.
     */
    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    /**
     * Get the users that belong to this category.
     */
    public function users()
    {
        return $this->hasMany(CompanySubUser::class, 'category_id');
    }
}
