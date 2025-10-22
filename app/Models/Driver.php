<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'full_name',
        'license_number',
        'license_type',
        'phone',
        'experience_years',
        'license_expiry',
        'emergency_contact',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'license_expiry' => 'date',
    ];

    /**
     * Get the company that owns the driver
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    /**
     * Get the user who created this driver
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this driver
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include drivers for a specific company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope a query to only include available drivers
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to only include assigned drivers
     */
    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    /**
     * Scope a query to only include inactive drivers
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope a query to only include drivers with expiring licenses (within 30 days)
     */
    public function scopeExpiringLicenses($query, $days = 30)
    {
        return $query->where('license_expiry', '<=', now()->addDays($days));
    }

    /**
     * Get the license type in a readable format
     */
    public function getLicenseTypeFormattedAttribute()
    {
        return match($this->license_type) {
            'class-a' => 'Class A',
            'class-b' => 'Class B',
            'class-c' => 'Class C',
            'motorcycle' => 'Motorcycle',
            default => ucfirst(str_replace('-', ' ', $this->license_type))
        };
    }

    /**
     * Get the status in a readable format
     */
    public function getStatusFormattedAttribute()
    {
        return match($this->status) {
            'available' => 'Available',
            'assigned' => 'Assigned',
            'on-leave' => 'On Leave',
            'inactive' => 'Inactive',
            default => ucfirst(str_replace('-', ' ', $this->status))
        };
    }

    /**
     * Check if license is expired
     */
    public function isLicenseExpired()
    {
        return $this->license_expiry < now();
    }

    /**
     * Check if license is expiring soon (within 30 days)
     */
    public function isLicenseExpiringSoon($days = 30)
    {
        return $this->license_expiry <= now()->addDays($days) && $this->license_expiry > now();
    }

    /**
     * Get days until license expiry
     */
    public function getDaysUntilLicenseExpiryAttribute()
    {
        if (!$this->license_expiry) {
            return null;
        }

        return now()->diffInDays($this->license_expiry, false);
    }
}
