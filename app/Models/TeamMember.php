<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TeamMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'full_name',
        'employee_id',
        'position',
        'department_id',
        'phone',
        'email',
        'hire_date',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    /**
     * Get the company that owns the team member
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    /**
     * Get the user who created this team member
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this team member
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the department that this team member belongs to
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(\App\Models\DepartmentCategory::class, 'department_id');
    }

    /**
     * Scope a query to only include team members for a specific company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope a query to only include active team members
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive team members
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get the employment duration in years
     */
    public function getEmploymentDurationAttribute()
    {
        if (!$this->hire_date) {
            return null;
        }

        return $this->hire_date->diffInYears(now());
    }
}