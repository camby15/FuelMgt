<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Training extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_trainings';

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'type', // workshop, seminar, course, certification, online
        'start_date',
        'end_date',
        'participant_count',
        'instructor',
        'location',
        'status', // planned, active, completed, cancelled
        'created_by',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Training types
    const TYPE_WORKSHOP = 'workshop';
    const TYPE_SEMINAR = 'seminar';
    const TYPE_COURSE = 'course';
    const TYPE_CERTIFICATION = 'certification';
    const TYPE_ONLINE = 'online';

    // Training statuses
    const STATUS_PLANNED = 'planned';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
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

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    // Get training type label
    public function getTypeLabelAttribute()
    {
        $types = [
            self::TYPE_WORKSHOP => 'Workshop',
            self::TYPE_SEMINAR => 'Seminar',
            self::TYPE_COURSE => 'Course',
            self::TYPE_CERTIFICATION => 'Certification',
            self::TYPE_ONLINE => 'Online'
        ];
        return $types[$this->type] ?? $this->type;
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        $statuses = [
            self::STATUS_PLANNED => 'Planned',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled'
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    // Get status badge class
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            self::STATUS_PLANNED => 'bg-secondary bg-opacity-10 text-secondary',
            self::STATUS_ACTIVE => 'bg-success bg-opacity-10 text-success',
            self::STATUS_COMPLETED => 'bg-primary bg-opacity-10 text-primary',
            self::STATUS_CANCELLED => 'bg-danger bg-opacity-10 text-danger'
        ];
        return $classes[$this->status] ?? 'bg-secondary bg-opacity-10 text-secondary';
    }

    // Get type badge class
    public function getTypeBadgeClassAttribute()
    {
        $classes = [
            self::TYPE_WORKSHOP => 'bg-primary bg-opacity-10 text-primary',
            self::TYPE_SEMINAR => 'bg-info bg-opacity-10 text-info',
            self::TYPE_COURSE => 'bg-success bg-opacity-10 text-success',
            self::TYPE_CERTIFICATION => 'bg-warning bg-opacity-10 text-warning',
            self::TYPE_ONLINE => 'bg-purple bg-opacity-10 text-purple'
        ];
        return $classes[$this->type] ?? 'bg-secondary bg-opacity-10 text-secondary';
    }
}
