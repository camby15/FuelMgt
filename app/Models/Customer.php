<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    // Customer type constants
    const TYPE_INDIVIDUAL = 'individual';
    const TYPE_CORPORATE = 'corporate';

    protected $fillable = [
        // Customer Type
        'customer_type',
        
        // Personal Details
        'title',
        'name',
        'email',
        'phone',
        'address',
        'occupation',
        'date_of_birth',
        'gender',
        'marital_status',

        // Company Profile Section
        'company_name',
        'corporate_telephone',
        'corporate_email',
        'headquarters_address',

        // Company Details
        'commencement_date',
        'sector',
        'number_of_employees',

        // Primary Contact
        'primary_contact_name',
        'primary_contact_position',
        'primary_contact_number',
        'primary_contact_email',
        'primary_contact_address',

        // Customer Management
        'customer_category',
        'value',
        'status',
        'change_type',
        'assigned_branch',
        'channel',
        'company_group_code',
        'mode_of_communication',
        'source_of_acquisition',

        // Relationship
        'company_id'
    ];

    /**
     * Get the company that owns the customer.
     */
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    /**
     * Scope a query to filter by customer category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('customer_category', $category);
    }

    /**
     * Scope a query to filter by customer status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public function pointsBalance($loyaltyProgramId)
{
    return \App\Models\CustomerPoint::where('customer_id', $this->id)
        ->where('loyalty_program_id', $loyaltyProgramId)
        ->value('points_balance') ?? 0;
}

    /**
     * Scope a query to filter individual customers.
     */
    public function scopeIndividuals($query)
    {
        return $query->where('customer_type', self::TYPE_INDIVIDUAL);
    }

    /**
     * Scope a query to filter corporate customers.
     */
    public function scopeCorporates($query)
    {
        return $query->where('customer_type', self::TYPE_CORPORATE);
    }

    /**
     * Check if the customer is individual.
     */
    public function isIndividual()
    {
        return $this->customer_type === self::TYPE_INDIVIDUAL;
    }

    /**
     * Check if the customer is corporate.
     */
    public function isCorporate()
    {
        return $this->customer_type === self::TYPE_CORPORATE;
    }

    /**
     * Get the formatted value attribute.
     */
    public function getFormattedValueAttribute()
    {
        return number_format($this->value, 2);
    }

    /**
     * Get the age of the customer relationship in days.
     */
    public function getRelationshipAgeAttribute()
    {
        return $this->commencement_date ? now()->diffInDays($this->commencement_date) : 0;
    }

    /**
     * Get the customer status badge HTML.
     */
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'Active' => 'success',
            'Inactive' => 'secondary',
            'Pending' => 'warning',
            'On Hold' => 'info',
            'Suspended' => 'danger',
            'Blacklisted' => 'dark',
            'VIP' => 'primary',
            'Regular' => 'light',
            'New' => 'info'
        ];

        $color = $colors[$this->status] ?? 'secondary';
        return "<span class='badge bg-{$color}'>{$this->status}</span>";
    }

    /**
     * Scope a query to filter active customers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope a query to filter VIP customers.
     */
    public function scopeVip($query)
    {
        return $query->where('customer_category', 'VIP');
    }

    /**
     * Scope a query to filter customers by value range.
     */
    public function scopeValueBetween($query, $min, $max)
    {
        return $query->whereBetween('value', [$min, $max]);
    }

    /**
     * Get all customer activities.
     */
    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    /**
     * Get the documents associated with the customer.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
