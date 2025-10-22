<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ExpenseClaim extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'expense_claims';

    protected $fillable = [
        'company_id',
        'employee_id',
        'claim_number',
        'description',
        'category',
        'amount',
        'currency',
        'expense_date',
        'status',
        'receipt_path',
        'notes',
        'approved_by',
        'approved_at',
        'rejected_reason',
        'paid_at'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2'
    ];

    // Expense categories
    const CATEGORY_TRAVEL = 'travel';
    const CATEGORY_MEALS = 'meals';
    const CATEGORY_ACCOMMODATION = 'accommodation';
    const CATEGORY_TRANSPORT = 'transport';
    const CATEGORY_OFFICE_SUPPLIES = 'office_supplies';
    const CATEGORY_TRAINING = 'training';
    const CATEGORY_COMMUNICATION = 'communication';
    const CATEGORY_OTHER = 'other';

    // Expense statuses
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PAID = 'paid';

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

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    // Get category label
    public function getCategoryLabelAttribute()
    {
        $categories = [
            self::CATEGORY_TRAVEL => 'Travel',
            self::CATEGORY_MEALS => 'Meals',
            self::CATEGORY_ACCOMMODATION => 'Accommodation',
            self::CATEGORY_TRANSPORT => 'Transport',
            self::CATEGORY_OFFICE_SUPPLIES => 'Office Supplies',
            self::CATEGORY_TRAINING => 'Training',
            self::CATEGORY_COMMUNICATION => 'Communication',
            self::CATEGORY_OTHER => 'Other'
        ];
        return $categories[$this->category] ?? $this->category;
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        $statuses = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_PAID => 'Paid'
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    // Get status badge class
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
            self::STATUS_PAID => 'bg-primary'
        ];
        return $classes[$this->status] ?? 'bg-secondary';
    }

    // Generate claim number
    public static function generateClaimNumber($companyId)
    {
        $year = Carbon::now()->year;
        $lastClaim = self::where('company_id', $companyId)
                        ->whereYear('created_at', $year)
                        ->orderBy('id', 'desc')
                        ->first();

        $nextNumber = $lastClaim ? (intval(substr($lastClaim->claim_number, -3)) + 1) : 1;
        
        return 'EC-' . $year . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    // Check if claim can be edited
    public function canBeEdited()
    {
        return in_array($this->status, [self::STATUS_PENDING]);
    }

    // Check if claim can be approved
    public function canBeApproved()
    {
        return $this->status === self::STATUS_PENDING;
    }

    // Check if claim can be paid
    public function canBePaid()
    {
        return $this->status === self::STATUS_APPROVED;
    }
}
