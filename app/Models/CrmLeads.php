<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmLeads extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'crm_leads';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // Basic Lead Information
        'company_id',
        'name',
        'email',
        'phone',
        'source',
        'notes',
        'status',
        'contact_person',
        'contact_person_email', 
        'contact_person_phone',

        // Appointment Details
        'appointment_date',
        'appointment_time',
        'appointment_type',
        'appointment_notes'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
    ];

    /**
     * Get the company that owns the lead.
     */
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    /**
     * Get the user assigned to this lead
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    /**
     * Scope a query to only include new leads.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNewLeads($query)
    {
        return $query->where('status', 'New');
    }

    /**
     * Scope a query to only include qualified leads.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeQualifiedLeads($query)
    {
        return $query->where('status', 'Qualified');
    }

    /**
     * Check if the lead has a scheduled appointment.
     *
     * @return bool
     */
    public function hasAppointment(): bool
    {
        return $this->appointment_date !== null && $this->appointment_time !== null;
    }

    /**
     * Determine the lead's age in days.
     *
     * @return int
     */
    public function getLeadAgeDays(): int
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Get the lead's source label.
     *
     * @return string
     */
    public function getSourceLabel(): string
    {
        $sources = [
            'Website' => 'Website',
            'Referral' => 'Referral',
            'Social Media' => 'Social Media',
            'Direct' => 'Direct',
            'Other' => 'Other'
        ];

        return $sources[$this->source] ?? 'Unknown';
    }
}