<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerPoint extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'customer_id',
        'loyalty_program_id',
        'points_balance',
        'points_earned',
        'points_redeemed',
        'last_activity',
        'expires_at'
    ];

    protected $casts = [
        'last_activity' => 'date',
        'expires_at' => 'date'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function pointsBalance($loyaltyProgramId)
    {
        return CustomerPoint::where('customer_id', $this->id)
            ->where('loyalty_program_id', $loyaltyProgramId)
            ->value('points_balance') ?? 0;
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(LoyaltyProgram::class, 'loyalty_program_id');
    }
}