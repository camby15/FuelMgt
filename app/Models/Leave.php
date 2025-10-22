<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Company;

class Leave extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_leaves';

    protected $fillable = [
        'company_id',
        'employee_id',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'total_days' => 'integer'
    ];

    // Leave types
    const TYPE_ANNUAL = 'annual';
    const TYPE_SICK = 'sick';
    const TYPE_PERSONAL = 'personal';
    const TYPE_MATERNITY = 'maternity';
    const TYPE_PATERNITY = 'paternity';
    const TYPE_EMERGENCY = 'emergency';
    const TYPE_BEREAVEMENT = 'bereavement';

    // Leave statuses
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scope for filtering by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope for filtering by leave type
    public function scopeByType($query, $type)
    {
        return $query->where('leave_type', $type);
    }

    // Scope for filtering by date range
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
    }

    // Scope for current company
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    // Get leave type label
    public function getLeaveTypeLabelAttribute()
    {
        $types = [
            self::TYPE_ANNUAL => 'Annual Leave',
            self::TYPE_SICK => 'Sick Leave',
            self::TYPE_PERSONAL => 'Personal Day',
            self::TYPE_MATERNITY => 'Maternity Leave',
            self::TYPE_PATERNITY => 'Paternity Leave',
            self::TYPE_EMERGENCY => 'Emergency Leave',
            self::TYPE_BEREAVEMENT => 'Bereavement Leave'
        ];

        return $types[$this->leave_type] ?? ucfirst($this->leave_type);
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        $statuses = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_CANCELLED => 'Cancelled'
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    // Get status badge class
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            self::STATUS_PENDING => 'bg-warning bg-opacity-10 text-warning',
            self::STATUS_APPROVED => 'bg-success bg-opacity-10 text-success',
            self::STATUS_REJECTED => 'bg-danger bg-opacity-10 text-danger',
            self::STATUS_CANCELLED => 'bg-secondary bg-opacity-10 text-secondary'
        ];

        return $classes[$this->status] ?? 'bg-secondary bg-opacity-10 text-secondary';
    }

    // Check if leave is pending
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    // Check if leave is approved
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    // Check if leave is rejected
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    // Check if leave is cancelled
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    // Calculate total days
    public function calculateTotalDays()
    {
        if ($this->start_date && $this->end_date) {
            $start = \Carbon\Carbon::parse($this->start_date);
            $end = \Carbon\Carbon::parse($this->end_date);
            return $start->diffInDays($end) + 1; // +1 to include both start and end dates
        }
        return 0;
    }

    // Check if leave overlaps with another leave
    public function overlapsWith($otherLeave)
    {
        return $this->start_date <= $otherLeave->end_date && 
               $this->end_date >= $otherLeave->start_date;
    }
}
