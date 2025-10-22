<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Contract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'customer_name',
        'emails',
        'start_date',
        'end_date',
        'status',
        'file_path',
        'notes',
        'created_by',
        'updated_by',
        'sent_for_signature_at',
        'auto_renewal',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'sent_for_signature_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'sent_for_signature_at' => 'datetime',
        'emails' => 'array',
    ];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function auditTrail(): HasMany
    {
        return $this->hasMany(ContractAuditTrail::class);
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(ContractSignature::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ContractAttachment::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ContractComment::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(ContractReminder::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_date', '<=', Carbon::now())
                    ->where('end_date', '>=', Carbon::now());
    }

    public function scopeExpiring($query, $days = 30)
    {
        return $query->where('status', 'active')
                    ->where('end_date', '<=', Carbon::now()->addDays($days))
                    ->where('end_date', '>=', Carbon::now());
    }

    public function scopePendingSignature($query)
    {
        return $query->where('status', 'pending_signature');
    }

    // Accessors & Mutators
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'active' => 'Active',
            'pending_signature' => 'Pending Signature',
            'signed' => 'Signed',
            'expired' => 'Expired',
            'cancelled' => 'Cancelled',
            'renewed' => 'Renewed',
            default => ucfirst($this->status),
        };
    }

    public function getDaysUntilExpirationAttribute(): int
    {
        return Carbon::now()->diffInDays($this->end_date, false);
    }
}

class ContractAuditTrail extends Model
{
    protected $fillable = [
        'contract_id',
        'user_id',
        'action',
        'details',
        'ip_address',
        'user_agent'
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

class ContractAttachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contract_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'uploaded_by'
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Accessor for human-readable file size
    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $index = 0;

        while ($bytes >= 1024 && $index < count($units) - 1) {
            $bytes /= 1024;
            $index++;
        }

        return round($bytes, 2) . ' ' . $units[$index];
    }
}

class ContractComment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contract_id',
        'user_id',
        'comment'
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

class ContractReminder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contract_id',
        'title',
        'description',
        'reminder_date',
        'reminder_type',
        'is_active',
        'last_sent_at',
        'created_by'
    ];

    protected $dates = [
        'reminder_date',
        'last_sent_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'reminder_date' => 'datetime',
        'last_sent_at' => 'datetime'
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeDue($query)
    {
        return $query->where('is_active', true)
                    ->where('reminder_date', '<=', Carbon::now())
                    ->where(function($q) {
                        $q->whereNull('last_sent_at')
                          ->orWhere('last_sent_at', '<=', Carbon::now()->subDay());
                    });
    }
}