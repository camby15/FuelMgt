<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamRoster extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'team_rosters';

    protected $fillable = [
        'company_id',
        'team_id',
        'roster_name',
        'start_date',
        'end_date',
        'roster_period',
        'working_days',
        'leave_days',
        'work_start_time',
        'work_end_time',
        'leave_type',
        'leave_reason',
        'roster_status',
        'max_working_hours',
        'roster_notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'working_days' => 'array',
        'leave_days' => 'array',
        'work_start_time' => 'datetime:H:i',
        'work_end_time' => 'datetime:H:i',
    ];

    /**
     * Get the company that owns the team roster
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    /**
     * Get the team for this roster
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(TeamParing::class, 'team_id');
    }

    /**
     * Get the user who created this roster
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this roster
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get roster assignments
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(TeamRosterAssignment::class, 'roster_id');
    }

    /**
     * Scope a query to only include rosters for a specific company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope a query to only include active rosters
     */
    public function scopeActive($query)
    {
        return $query->where('roster_status', 'active');
    }

    /**
     * Scope a query to only include draft rosters
     */
    public function scopeDraft($query)
    {
        return $query->where('roster_status', 'draft');
    }

    /**
     * Scope a query to only include inactive rosters
     */
    public function scopeInactive($query)
    {
        return $query->where('roster_status', 'inactive');
    }

    /**
     * Scope a query to filter by period
     */
    public function scopeByPeriod($query, $period)
    {
        return $query->where('roster_period', $period);
    }

    /**
     * Scope a query to filter by date range
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->where(function($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function($q2) use ($startDate, $endDate) {
                  $q2->where('start_date', '<=', $startDate)
                     ->where('end_date', '>=', $endDate);
              });
        });
    }

    /**
     * Get the roster status in a readable format
     */
    public function getStatusFormattedAttribute()
    {
        return match($this->roster_status) {
            'draft' => 'Draft',
            'active' => 'Active',
            'inactive' => 'Inactive',
            default => ucfirst($this->roster_status)
        };
    }

    /**
     * Get the roster period in a readable format
     */
    public function getPeriodFormattedAttribute()
    {
        return match($this->roster_period) {
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            default => ucfirst($this->roster_period)
        };
    }

    /**
     * Get the leave type in a readable format
     */
    public function getLeaveTypeFormattedAttribute()
    {
        return match($this->leave_type) {
            'vacation' => 'Vacation',
            'sick' => 'Sick Leave',
            'personal' => 'Personal',
            'holiday' => 'Holiday',
            'training' => 'Training',
            default => ucfirst($this->leave_type ?? 'None')
        };
    }

    /**
     * Get working days count
     */
    public function getWorkingDaysCountAttribute()
    {
        return is_array($this->working_days) ? count($this->working_days) : 0;
    }

    /**
     * Get leave days count
     */
    public function getLeaveDaysCountAttribute()
    {
        return is_array($this->leave_days) ? count($this->leave_days) : 0;
    }

    /**
     * Get total roster duration in days
     */
    public function getDurationDaysAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }
        
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Check if roster is currently active
     */
    public function isCurrentlyActive()
    {
        if ($this->roster_status !== 'active') {
            return false;
        }
        
        $today = now()->toDateString();
        return $this->start_date <= $today && $this->end_date >= $today;
    }

    /**
     * Get roster summary for display
     */
    public function getRosterSummaryAttribute()
    {
        $workingDays = $this->working_days_count;
        $leaveDays = $this->leave_days_count;
        $duration = $this->duration_days;
        
        return "{$workingDays} working days, {$leaveDays} leave days, {$duration} total days";
    }
}
