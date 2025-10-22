<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Performance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_performances';

    protected $fillable = [
        'company_id',
        'employee_id',
        'type', // self, manager, peer, 360
        'review_period_start',
        'review_period_end',
        'goals',
        'achievements',
        'areas_for_improvement',
        'overall_score', // 1-5
        'overall_rating', // excellent, good, satisfactory, needs_improvement, poor
        'status', // draft, pending, completed, cancelled
        'reviewer_id',
        'notes',
        'kpis' // JSON field for KPI data
    ];

    protected $casts = [
        'review_period_start' => 'date',
        'review_period_end' => 'date',
        'overall_score' => 'decimal:1',
        'kpis' => 'array', // Cast KPIs as JSON array
    ];

    // Performance types
    const TYPE_SELF = 'self';
    const TYPE_MANAGER = 'manager';
    const TYPE_PEER = 'peer';
    const TYPE_360 = '360';

    // Performance statuses
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Performance ratings
    const RATING_EXCELLENT = 'excellent';
    const RATING_GOOD = 'good';
    const RATING_SATISFACTORY = 'satisfactory';
    const RATING_NEEDS_IMPROVEMENT = 'needs_improvement';
    const RATING_POOR = 'poor';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('overall_rating', $rating);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    // Get type label
    public function getTypeLabelAttribute()
    {
        $types = [
            self::TYPE_SELF => 'Self Assessment',
            self::TYPE_MANAGER => 'Manager Review',
            self::TYPE_PEER => 'Peer Review',
            self::TYPE_360 => '360 Review'
        ];
        return $types[$this->type] ?? $this->type;
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        $statuses = [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled'
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    // Get status badge class
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            self::STATUS_DRAFT => 'bg-secondary bg-opacity-10 text-secondary',
            self::STATUS_PENDING => 'bg-warning bg-opacity-10 text-warning',
            self::STATUS_COMPLETED => 'bg-success bg-opacity-10 text-success',
            self::STATUS_CANCELLED => 'bg-danger bg-opacity-10 text-danger'
        ];
        return $classes[$this->status] ?? 'bg-secondary bg-opacity-10 text-secondary';
    }

    // Get rating label
    public function getRatingLabelAttribute()
    {
        $ratings = [
            self::RATING_EXCELLENT => 'Excellent',
            self::RATING_GOOD => 'Good',
            self::RATING_SATISFACTORY => 'Satisfactory',
            self::RATING_NEEDS_IMPROVEMENT => 'Needs Improvement',
            self::RATING_POOR => 'Poor'
        ];
        return $ratings[$this->overall_rating] ?? $this->overall_rating;
    }

    // Get rating badge class
    public function getRatingBadgeClassAttribute()
    {
        $classes = [
            self::RATING_EXCELLENT => 'bg-success bg-opacity-10 text-success',
            self::RATING_GOOD => 'bg-primary bg-opacity-10 text-primary',
            self::RATING_SATISFACTORY => 'bg-info bg-opacity-10 text-info',
            self::RATING_NEEDS_IMPROVEMENT => 'bg-warning bg-opacity-10 text-warning',
            self::RATING_POOR => 'bg-danger bg-opacity-10 text-danger'
        ];
        return $classes[$this->overall_rating] ?? 'bg-secondary bg-opacity-10 text-secondary';
    }
}
