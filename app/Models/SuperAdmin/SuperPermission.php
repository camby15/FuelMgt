<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SuperPermission extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'super_permissions';
    
    protected $fillable = [
        'name',
        'group',
        'display_name',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The roles that belong to the permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            SuperRole::class,
            'super_role_permission',
            'permission_id',
            'role_id'
        )->withTimestamps();
    }

    /**
     * Get permissions by group.
     */
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Find a permission by name.
     */
    public static function findByName(string $name, $columns = ['*'])
    {
        return static::where('name', $name)->first($columns);
    }

    /**
     * Get all unique permission groups.
     */
    public static function getGroups(): array
    {
        return static::select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group')
            ->filter()
            ->toArray();
    }
}