<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OutboundOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'outbound_number',
        'company_id',
        'requisition_id',
        'department',
        'requested_by',
        'items',
        'total_value',
        'status',
        'priority',
        'assigned_to',
        'assigned_at',
        'picked_by',
        'picked_at',
        'packed_by',
        'packed_at',
        'shipped_by',
        'shipped_at',
        'delivery_address',
        'delivery_contact',
        'delivery_phone',
        'requested_delivery_date',
        'actual_delivery_date',
        'notes',
        'special_instructions',
        'attachments',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'items' => 'array',
        'attachments' => 'array',
        'assigned_at' => 'datetime',
        'picked_at' => 'datetime',
        'packed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'requested_delivery_date' => 'datetime',
        'actual_delivery_date' => 'datetime',
        'total_value' => 'decimal:2',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_PICKED = 'picked';
    const STATUS_PACKED = 'packed';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    // Priority constants
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($outboundOrder) {
            if (empty($outboundOrder->outbound_number)) {
                $outboundOrder->outbound_number = 'OUT-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    // Relationships
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function pickedBy()
    {
        return $this->belongsTo(User::class, 'picked_by');
    }

    public function packedBy()
    {
        return $this->belongsTo(User::class, 'packed_by');
    }

    public function shippedBy()
    {
        return $this->belongsTo(User::class, 'shipped_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function waybills()
    {
        return $this->hasMany(Waybill::class, 'outbound_order_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_PROCESSING => 'badge-info',
            self::STATUS_PICKED => 'badge-primary',
            self::STATUS_PACKED => 'badge-secondary',
            self::STATUS_SHIPPED => 'badge-success',
            self::STATUS_DELIVERED => 'badge-success',
            self::STATUS_CANCELLED => 'badge-danger',
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

    public function getItemCountAttribute()
    {
        return is_array($this->items) ? count($this->items) : 0;
    }

    // Helper methods
    public function canBePicked()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    public function canBePacked()
    {
        return $this->status === self::STATUS_PICKED;
    }

    public function canBeShipped()
    {
        return $this->status === self::STATUS_PACKED;
    }

    public function canBeCancelled()
    {
        return !in_array($this->status, [self::STATUS_DELIVERED, self::STATUS_CANCELLED]);
    }
}