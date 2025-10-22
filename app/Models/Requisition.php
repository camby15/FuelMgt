<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\TeamMember;
use App\Models\User;

class Requisition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'requisition_number',
        'title',
        'company_id',
        'requester_id',
        'project_manager_id',
        'team_leader_id',
        'department_id',
        'department',
        'priority',
        'status',
        'is_reorder',
        'batch_number',
        'notes',
        'items',
        'attachments',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'issued_by',
        'issued_at',
        'reference_code',
        'requisition_date',
        'required_date',
        'management_approved_by',
        'management_approved_at',
        'management_rejection_reason',
        'team_allocations',
        'management_status',
        'management_notes',
        'total_amount',
    ];

    protected $casts = [
        'items' => 'array',
        'attachments' => 'array',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'issued_at' => 'datetime',
        'requisition_date' => 'date',
        'required_date' => 'date',
        'management_approved_at' => 'datetime',
        'team_allocations' => 'array',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_MANAGEMENT_APPROVED = 'management_approved';
    const STATUS_APPROVED = 'approved';
    const STATUS_PARTIALLY_APPROVED = 'partially_approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';

    // Priority constants
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    // Department constants (deprecated - use department_id relationship instead)
    // const DEPARTMENT_GPON = 'GPON';
    // const DEPARTMENT_HOME_CONNECTION = 'Home Connection';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($requisition) {
            if (empty($requisition->requisition_number)) {
                $requisition->requisition_number = 'PR-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    // Relationships
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function requestor()
    {
        return $this->belongsTo(Employee::class, 'requester_id');
    }


    public function teamLeader()
    {
        return $this->belongsTo(TeamMember::class, 'team_leader_id');
    }

    public function teamMembers()
    {
        // Get team members through the team pairing system
        if ($this->teamLeader) {
            // Find the team pairing where this team leader is the lead
            $teamPairing = \App\Models\TeamParing::where('team_lead', $this->team_leader_id)
                ->where('company_id', $this->company_id)
                ->first();
            
            if ($teamPairing) {
                return $teamPairing->teamMembers();
            }
        }
        
        // Return empty relationship if no team pairing found
        return $this->belongsToMany(TeamMember::class, 'team_paring_members', 'team_paring_id', 'team_member_id')
                    ->whereRaw('1 = 0'); // This will return empty results
    }

    public function projectManager()
    {
        return $this->belongsTo(\App\Models\CompanySubUser::class, 'project_manager_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function managementApprover()
    {
        return $this->belongsTo(User::class, 'management_approved_by');
    }


    public function departmentCategory()
    {
        return $this->belongsTo(DepartmentCategory::class, 'department_id');
    }

    public function waybills()
    {
        return $this->hasMany(Waybill::class, 'requisition_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_DRAFT => 'badge-secondary',
            self::STATUS_NEW => 'badge-primary',
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_MANAGEMENT_APPROVED => 'badge-info',
            self::STATUS_APPROVED => 'badge-success',
            self::STATUS_PARTIALLY_APPROVED => 'badge-warning',
            self::STATUS_REJECTED => 'badge-danger',
            self::STATUS_COMPLETED => 'badge-info'
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getPriorityBadgeAttribute()
    {
        $badges = [
            self::PRIORITY_LOW => 'badge-success',
            self::PRIORITY_MEDIUM => 'badge-info',
            self::PRIORITY_HIGH => 'badge-warning',
            self::PRIORITY_URGENT => 'badge-danger'
        ];

        return $badges[$this->priority] ?? 'badge-secondary';
    }
}
