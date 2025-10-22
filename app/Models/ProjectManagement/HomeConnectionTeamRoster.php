<?php

namespace App\Models\ProjectManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TeamParing;
use App\Models\User;

class HomeConnectionTeamRoster extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'home_connection_team_rosters';

    protected $fillable = [
        'team_id',
        'site_assignment_id',
        'company_id',
        'schedule_date',
        'shift_type',
        'start_time',
        'end_time',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Roster belongs to a team
     */
    public function team()
    {
        return $this->belongsTo(TeamParing::class, 'team_id');
    }

    /**
     * Relationship: Roster belongs to a site assignment
     */
    public function siteAssignment()
    {
        return $this->belongsTo(SiteAssignment::class, 'site_assignment_id');
    }

    /**
     * Relationship: Roster belongs to a company
     */
    public function company()
    {
        return $this->belongsTo(\App\Models\CompanyProfile::class, 'company_id');
    }

    /**
     * Relationship: User who created the roster
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship: User who last updated the roster
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope: Filter by company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope: Filter by team
     */
    public function scopeForTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('schedule_date', [$startDate, $endDate]);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get formatted shift type
     */
    public function getFormattedShiftTypeAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->shift_type));
    }

    /**
     * Check if roster is in the past
     */
    public function isPast()
    {
        return $this->schedule_date->isPast();
    }

    /**
     * Check if roster is today
     */
    public function isToday()
    {
        return $this->schedule_date->isToday();
    }

    /**
     * Check if roster is upcoming
     */
    public function isUpcoming()
    {
        return $this->schedule_date->isFuture();
    }

    /**
     * Validation rules for creating/updating roster
     */
    public static function validationRules($rosterId = null)
    {
        return [
            'team_id' => 'required|exists:team_paring,id',
            'site_assignment_id' => 'required|exists:site_assignments,id',
            'company_id' => 'required|exists:company_profiles,id',
            'schedule_date' => 'required|date',
            'shift_type' => 'required|in:full_day,morning,afternoon,night',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'status' => 'required|in:Scheduled,Pending,In Progress,Completed,Cancelled',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
