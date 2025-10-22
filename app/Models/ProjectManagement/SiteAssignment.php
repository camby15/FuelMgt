<?php

namespace App\Models\ProjectManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CompanyProfile;
use App\Models\Customer;
use App\Models\ProjectManagement\HomeConnectionCustomer;
use App\Models\TeamParing;
use App\Models\User;

class SiteAssignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'customer_id',
        'team_id',
        'assigned_by',
        'resolved_by',
        'assignment_title',
        'description',
        'status',
        'priority',
        'site_address',
        'site_contact_person',
        'site_contact_number',
        'assigned_date',
        'start_date',
        'due_date',
        'completed_date',
        'has_issue',
        'issue_description',
        'issue_status',
        'resolution_notes',
        'issue_reported_at',
        'issue_resolved_at',
    ];

    protected $casts = [
        'assigned_date' => 'datetime',
        'start_date' => 'datetime',
        'due_date' => 'datetime',
        'completed_date' => 'datetime',
        'issue_reported_at' => 'datetime',
        'issue_resolved_at' => 'datetime',
        'has_issue' => 'boolean',
    ];

    // Status and Priority Constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_CRITICAL = 'critical';

    public const ISSUE_STATUS_REPORTED = 'reported';
    public const ISSUE_STATUS_INVESTIGATING = 'investigating';
    public const ISSUE_STATUS_RESOLVED = 'resolved';

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(HomeConnectionCustomer::class, 'customer_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(TeamParing::class, 'team_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', '!=', self::STATUS_COMPLETED)
                    ->where('status', '!=', self::STATUS_CANCELLED);
    }

    public function scopeWithIssues($query)
    {
        return $query->where('has_issue', true)
                    ->where('issue_status', '!=', self::ISSUE_STATUS_RESOLVED);
    }

    // Helper Methods
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function hasCriticalIssue(): bool
    {
        return $this->has_issue && $this->priority === self::PRIORITY_CRITICAL;
    }

    public function getStatusBadgeAttribute(): string
    {
        $badgeClass = [
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_IN_PROGRESS => 'bg-info',
            self::STATUS_COMPLETED => 'bg-success',
            self::STATUS_CANCELLED => 'bg-secondary',
        ][$this->status] ?? 'bg-secondary';

        return '<span class="badge ' . $badgeClass . '">' . ucfirst(str_replace('_', ' ', $this->status)) . '</span>';
    }

    public function getPriorityBadgeAttribute(): string
    {
        $badgeClass = [
            self::PRIORITY_LOW => 'bg-secondary',
            self::PRIORITY_MEDIUM => 'bg-primary',
            self::PRIORITY_HIGH => 'bg-warning',
            self::PRIORITY_CRITICAL => 'bg-danger',
        ][$this->priority] ?? 'bg-secondary';

        return '<span class="badge ' . $badgeClass . '">' . ucfirst($this->priority) . '</span>';
    }
}
