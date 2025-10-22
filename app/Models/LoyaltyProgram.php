<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyProgram extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
        'name',
        'program_type',
        'customer_type',
        'description',
        'start_date',
        'end_date',
        'points',
        'currency_value',
        'status',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'customer_type' => 'array', 
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    public function tiers(): HasMany
    {
        return $this->hasMany(CustomerTier::class);
    }

    public function rewards(): HasMany
    {
        return $this->hasMany(Reward::class);
    }

    public function customerPoints(): HasMany
    {
        return $this->hasMany(CustomerPoint::class);
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(Redemption::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class);
    }
}
