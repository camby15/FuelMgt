<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SuperRole extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'super_roles';
    
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active',
        'is_system',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
    ];

    /**
     * The permissions that belong to the role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            SuperPermission::class,
            'super_role_permission',
            'role_id',
            'permission_id'
        )->withTimestamps();
    }

    /**
     * Check if role has a specific permission.
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    /**
     * Assign permissions to the role.
     */
    public function assignPermissions(array $permissionIds): array
    {
        return $this->permissions()->syncWithoutDetaching($permissionIds);
    }

    /**
     * Revoke permissions from the role.
     */
    public function revokePermissions(array $permissionIds = []): int
    {
        return $permissionIds 
            ? $this->permissions()->detach($permissionIds)
            : $this->permissions()->detach();
    }

    /**
     * Get only active roles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get only system roles.
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Find a role by name.
     */
    public static function findByName(string $name, $columns = ['*'])
    {
        return static::where('name', $name)->first($columns);
    }
}