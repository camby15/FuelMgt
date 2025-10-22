<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

/**
 * Model for managing company partnership information
 */
class CompanyPartners extends Model
{
    use HasFactory;

    /**
     * Disable global scopes by default
     *
     * @var bool
     */
    protected static $globalScopes = [];

    /**
     * Prevent automatic query constraints
     */
    protected static function booted()
    {
        // Remove global model event logging
        // static::registerModelEvent('retrieved', function ($model) {
        //     \Log::error('Unexpected Model Retrieval', [
        //         'model' => get_class($model),
        //         'id' => $model->id ?? 'N/A',
        //         'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
        //     ]);
        // });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_partners';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'status',
        'website',
        'industry',
        'partnership_date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'partnership_date' => 'date'
    ];

    /**
     * Get the user that owns the partner.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the company that owns the partner.
     */
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    /**
     * Validation rules for creating/updating a partner
     */
    public static function validationRules($id = null)
    {
        return [
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:company_profiles,id',
            'company_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('company_partners')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })->ignore($id)
            ],
            'contact_person' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('company_partners')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })->ignore($id)
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'industry' => 'nullable|string|max:100',
            'partnership_date' => 'nullable|date',
            'status' => 'boolean'
        ];
    }

    /**
     * Scope a query to only include active partners.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        // Log when active scope is called
        \Log::info('Active Scope Called', [
            'query' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
        ]);

        return $query->where('status', true);
    }

    /**
     * Mutator for normalizing email
     *
     * @param string $value
     * @return void
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower(trim($value));
    }

    /**
     * Accessor for formatted phone number
     *
     * @return string
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) return null;

        // Basic phone formatting (adjust as needed)
        $cleaned = preg_replace('/[^0-9]/', '', $this->phone);
        return preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $cleaned);
    }

    /**
     * Prevent any automatic query constraints
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        // Log when a new query is created
        \Log::info('New Query Created for CompanyPartners', [
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
        ]);

        return parent::newQuery();
    }

    /**
     * Disable any global scopes by default
     *
     * @return $this
     */
    public function withoutAnyGlobalScopes()
    {
        return $this->newQueryWithoutScopes();
    }
}
