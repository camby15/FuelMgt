<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserProfile;
use App\Traits\HandleProfileImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class CompanySubUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HandleProfileImage;

    protected $table = 'company_sub_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'email',
        'phone_number',
        'pin_code',
        'role',
        'status',
        'company_id',
        'profile_image',
        'last_login_at',
        'email_verified_at',
        'profile_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pin_code',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'pin_code' => 'hashed',
    ];

    /**
     * Get the company that owns the sub user.
     */
    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    /**
     * Get the profile associated with the sub user.
     */
    public function profile()
    {
        return $this->belongsTo(UserProfile::class, 'profile_id');
    }

    /**
     * Get the profile image URL.
     *
     * @return string
     */
    public function getProfileImageUrl()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        return asset('images/users/default-avatar.jpg');
    }

    /**
     * Check if the sub user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if the sub user is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the sub user is locked.
     *
     * @return bool
     */
    public function isLocked()
    {
        return $this->status === 'locked';
    }

    /**
     * Get the sub user's status badge class.
     *
     * @return string
     */
    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'active' => 'bg-success',
            'inactive' => 'bg-warning',
            'locked' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Scope a query to only include active sub users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include sub users of a specific company.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $companyId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Get the user's active menu access permissions.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getActiveMenuAccess()
    {
        if (!$this->profile) {
            return collect();
        }

        return $this->profile->menuAccess()
            ->where('is_active', true)
            ->get()
            ->keyBy('menu_key');
    }

    /**
     * Check if the user has access to a specific menu.
     *
     * @param string $menuKey
     * @return bool
     */
    public function hasMenuAccess($menuKey)
    {
        return $this->getActiveMenuAccess()->has($menuKey);
    }
}
