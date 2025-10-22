<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TeamParing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'team_paring';

    protected $fillable = [
        'company_id',
        'team_name',
        'team_code',
        'team_location',
        'team_status',
        'team_allocation',
        'team_lead',
        'primary_vehicle',
        'primary_driver',
        'formation_date',
        'contact_number',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'formation_date' => 'date',
    ];

    /**
     * Get the company that owns the team pairing
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    /**
     * Get the user who created this team pairing
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this team pairing
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the team lead member
     */
    public function teamLead(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class, 'team_lead');
    }

    /**
     * Get the primary vehicle
     */
    public function primaryVehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'primary_vehicle');
    }

    /**
     * Get the primary driver
     */
    public function primaryDriver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'primary_driver');
    }

    /**
     * Get the team members assigned to this team
     */
    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(TeamMember::class, 'team_paring_members', 'team_paring_id', 'team_member_id')
                    ->withTimestamps();
    }

    /**
     * Get the vehicles assigned to this team
     */
    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'team_paring_vehicles', 'team_paring_id', 'vehicle_id')
                    ->withTimestamps();
    }

    /**
     * Get the drivers assigned to this team
     */
    public function drivers(): BelongsToMany
    {
        return $this->belongsToMany(Driver::class, 'team_paring_drivers', 'team_paring_id', 'driver_id')
                    ->withTimestamps();
    }

    /**
     * Scope a query to only include team pairings for a specific company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope a query to only include active team pairings
     */
    public function scopeActive($query)
    {
        return $query->where('team_status', 'active');
    }

    /**
     * Scope a query to only include deployed team pairings
     */
    public function scopeDeployed($query)
    {
        return $query->where('team_status', 'deployed');
    }

    /**
     * Scope a query to only include inactive team pairings
     */
    public function scopeInactive($query)
    {
        return $query->where('team_status', 'inactive');
    }

    /**
     * Scope a query to only include team pairings in maintenance
     */
    public function scopeInMaintenance($query)
    {
        return $query->where('team_status', 'maintenance');
    }

    /**
     * Scope a query to filter by location
     */
    public function scopeByLocation($query, $location)
    {
        return $query->where('team_location', $location);
    }

    /**
     * Get the team status in a readable format
     */
    public function getStatusFormattedAttribute()
    {
        return match($this->team_status) {
            'active' => 'Active',
            'inactive' => 'Inactive',
            'deployed' => 'Deployed',
            'maintenance' => 'Maintenance',
            default => ucfirst($this->team_status)
        };
    }

    /**
     * Get the team location in a readable format
     */
    public function getLocationFormattedAttribute()
    {
        return match($this->team_location) {
            'accra' => 'Accra',
            'kumasi' => 'Kumasi',
            'takoradi' => 'Takoradi',
            'tamale' => 'Tamale',
            'cape-coast' => 'Cape Coast',
            default => ucfirst(str_replace('-', ' ', $this->team_location))
        };
    }

    /**
     * Get the team size (number of members)
     */
    public function getTeamSizeAttribute()
    {
        return $this->teamMembers()->count();
    }

    /**
     * Check if team has all required assignments
     */
    public function isFullyAssigned()
    {
        return $this->teamMembers()->count() > 0 && 
               $this->vehicles()->count() > 0 && 
               $this->drivers()->count() > 0;
    }

    /**
     * Get team assignment completeness percentage
     */
    public function getAssignmentCompletenessAttribute()
    {
        $total = 3; // members, vehicles, drivers
        $assigned = 0;
        
        if ($this->teamMembers()->count() > 0) $assigned++;
        if ($this->vehicles()->count() > 0) $assigned++;
        if ($this->drivers()->count() > 0) $assigned++;
        
        return round(($assigned / $total) * 100);
    }

    /**
     * Get team summary for display
     */
    public function getTeamSummaryAttribute()
    {
        $membersCount = $this->teamMembers()->count();
        $vehiclesCount = $this->vehicles()->count();
        $driversCount = $this->drivers()->count();
        
        return "{$membersCount} members, {$vehiclesCount} vehicles, {$driversCount} drivers";
    }
}