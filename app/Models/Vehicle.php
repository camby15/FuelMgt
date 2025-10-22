<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'registration_number',
        'make',
        'model',
        'type',
        'year',
        'color',
        'fuel_type',
        'insurance_expiry',
        'mileage',
        'status',
        'notes',
        'assigned_driver_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'insurance_expiry' => 'date',
    ];

    /**
     * Get the company that owns the vehicle
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    /**
     * Get the user who created this vehicle
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this vehicle
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the assigned driver for this vehicle
     */
    public function assignedDriver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'assigned_driver_id');
    }

    /**
     * Scope a query to only include vehicles for a specific company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope a query to only include available vehicles
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to only include vehicles in use
     */
    public function scopeInUse($query)
    {
        return $query->where('status', 'in-use');
    }

    /**
     * Scope a query to only include vehicles in maintenance
     */
    public function scopeInMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    /**
     * Scope a query to only include inactive vehicles
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope a query to only include vehicles with expiring insurance (within 30 days)
     */
    public function scopeExpiringInsurance($query, $days = 30)
    {
        return $query->where('insurance_expiry', '<=', now()->addDays($days));
    }

    /**
     * Get the vehicle type in a readable format
     */
    public function getTypeFormattedAttribute()
    {
        return match($this->type) {
            'sedan' => 'Sedan',
            'suv' => 'SUV',
            'truck' => 'Truck',
            'van' => 'Van',
            'motorcycle' => 'Motorcycle',
            default => ucfirst($this->type)
        };
    }

    /**
     * Get the status in a readable format
     */
    public function getStatusFormattedAttribute()
    {
        return match($this->status) {
            'available' => 'Available',
            'in-use' => 'In Use',
            'maintenance' => 'Maintenance',
            'inactive' => 'Inactive',
            default => ucfirst(str_replace('-', ' ', $this->status))
        };
    }

    /**
     * Get the make and model combined
     */
    public function getMakeModelAttribute()
    {
        return $this->make . ' ' . $this->model;
    }

    /**
     * Check if insurance is expired
     */
    public function isInsuranceExpired()
    {
        return $this->insurance_expiry < now();
    }

    /**
     * Check if insurance is expiring soon (within 30 days)
     */
    public function isInsuranceExpiringSoon($days = 30)
    {
        return $this->insurance_expiry <= now()->addDays($days) && $this->insurance_expiry > now();
    }

    /**
     * Get days until insurance expiry
     */
    public function getDaysUntilInsuranceExpiryAttribute()
    {
        if (!$this->insurance_expiry) {
            return null;
        }

        return now()->diffInDays($this->insurance_expiry, false);
    }

    /**
     * Get the vehicle age in years
     */
    public function getAgeAttribute()
    {
        if (!$this->year) {
            return null;
        }

        return now()->year - $this->year;
    }
}
