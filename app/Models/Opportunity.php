<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

/**
 * Model for managing opportunity information
 */
class Opportunity extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'opportunities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'name',
        'account',
        'stage',
        'amount',
        'expected_revenue',
        'close_date',
        'probability',
        'status',
        'description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'close_date' => 'date',
        'amount' => 'float',
        'expected_revenue' => 'float',
        'probability' => 'float'
    ];

    /**
     * Get the user that owns the opportunity.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the company that owns the opportunity.
     */
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    /**
     * Validation rules for creating/updating an opportunity
     */
    public static function validationRules($id = null)
    {
        return [
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:company_profiles,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('opportunities')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })->ignore($id)
            ],
            'account' => 'required|string|max:255',
            'stage' => [
                'required', 
                'in:Prospecting,Qualification,Proposal,Negotiation,Closed Won,Closed Lost'
            ],
            'amount' => 'required|numeric|min:0',
            'expected_revenue' => 'nullable|numeric|min:0',
            'close_date' => 'nullable|date|after:today',
            'probability' => 'nullable|numeric|min:0|max:100',
            'status' => 'boolean',
            'description' => 'nullable|string'
        ];
    }

    /**
     * Scope a query to only include active opportunities.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Mutator for normalizing opportunity name
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
    }

    /**
     * Accessor for formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2, '.', ',');
    }

    /**
     * Calculate total opportunity value
     */
    public static function calculateTotalValue($companyId)
    {
        return self::where('company_id', $companyId)
            ->where('status', true)
            ->sum('amount');
    }

    /**
     * Get opportunities by stage
     */
    public static function getOpportunitiesByStage($companyId)
    {
        return self::where('company_id', $companyId)
            ->groupBy('stage')
            ->selectRaw('stage, COUNT(*) as count')
            ->get();
    }

    /**
     * Calculate win rate
     */
    public static function calculateWinRate($companyId)
    {
        $totalOpportunities = self::where('company_id', $companyId)->count();
        $wonOpportunities = self::where('company_id', $companyId)
            ->where('stage', 'Closed Won')
            ->count();

        return $totalOpportunities > 0 
            ? round(($wonOpportunities / $totalOpportunities) * 100, 2) 
            : 0;
    }
}