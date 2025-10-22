<?php

namespace App\Models\ProjectManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Models\CompanyProfile;
use App\Models\User;
use App\Models\ProjectManagement\SiteAssignment;

/**
 * Model for managing home connection customers
 */
class HomeConnectionCustomer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'home_connection_customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'business_unit',
        'msisdn',
        'customer_name',
        'email',
        'contact_number',
        'connection_type',
        'location',
        'gps_address',
        'latitude',
        'longitude',
        'status',
        'customer_id',
        'secondary_phone',
        'gender',
        'date_of_birth',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'account_number',
        'meter_number',
        'tariff_type',
        'service_type',
        'billing_type',
        'notes',
        'id_type',
        'id_number',
        'occupation',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'status' => 'string'
    ];

    /**
     * Get the company that owns the customer.
     */
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    /**
     * Get the user who created the customer.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the customer.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the site assignments for this customer.
     */
    public function assignments()
    {
        return $this->hasMany(SiteAssignment::class, 'customer_id');
    }

    /**
     * Validation rules for creating/updating a customer
     */
    public static function validationRules($id = null, $companyId = null)
    {
        return [
            'company_id' => 'required|exists:company_profiles,id',
            'business_unit' => 'required|in:GESL,LINFRA',
            'msisdn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('home_connection_customers', 'msisdn')
                    ->ignore($id)
                    ->where(function ($q) use ($companyId) {
                        if ($companyId) {
                            $q->where('company_id', $companyId);
                        }
                        $q->whereNull('deleted_at');
                    })
            ],
            'customer_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_number' => 'required|string|max:20',
            'connection_type' => 'required|in:Traditional,Quick ODN',
            'location' => 'required|string|max:255',
            'gps_address' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'required|in:Active,Inactive,Pending,Schedule',
            'customer_id' => 'nullable|string|max:50',
            'secondary_phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'meter_number' => 'nullable|string|max:50',
            'tariff_type' => 'nullable|string|max:100',
            'service_type' => 'nullable|string|max:100',
            'billing_type' => 'nullable|in:prepaid,postpaid',
            'notes' => 'nullable|string',
            'id_type' => 'nullable|string|max:50',
            'id_number' => 'nullable|string|max:50',
            'occupation' => 'nullable|string|max:100',
            'created_by' => 'required|exists:users,id',
            'updated_by' => 'nullable|exists:users,id'
        ];
    }

    /**
     * Scope a query to only include active customers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope a query to only include records for a specific company.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|string  $companyId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope a query to filter by business unit.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $businessUnit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBusinessUnit($query, $businessUnit)
    {
        return $query->where('business_unit', $businessUnit);
    }

    /**
     * Scope a query to filter by connection type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $connectionType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConnectionType($query, $connectionType)
    {
        return $query->where('connection_type', $connectionType);
    }

    /**
     * Mutator for normalizing email
     *
     * @param string $value
     * @return void
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value ? strtolower(trim($value)) : null;
    }

    /**
     * Mutator for normalizing MSISDN
     *
     * @param string $value
     * @return void
     */
    public function setMsisdnAttribute($value)
    {
        // Ensure MSISDN starts with +233
        if ($value && strpos($value, '+233') !== 0) {
            $this->attributes['msisdn'] = '+233' . ltrim($value, '+233');
        } else {
            $this->attributes['msisdn'] = $value;
        }
    }

    /**
     * Accessor for formatted phone number
     *
     * @return string
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->contact_number) return null;

        // Basic phone formatting (adjust as needed)
        $cleaned = preg_replace('/[^0-9]/', '', $this->contact_number);
        return preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $cleaned);
    }

    /**
     * Accessor for GPS coordinates as formatted string
     *
     * @return string
     */
    public function getGpsCoordinatesFormattedAttribute()
    {
        if (!$this->latitude || !$this->longitude) return null;
        
        return sprintf('%.4f° %s, %.4f° %s', 
            abs($this->latitude), 
            $this->latitude >= 0 ? 'N' : 'S',
            abs($this->longitude), 
            $this->longitude >= 0 ? 'E' : 'W'
        );
    }

    /**
     * Accessor for status badge class
     *
     * @return string
     */
    public function getStatusBadgeClassAttribute()
    {
        switch($this->status) {
            case 'Active':
                return 'bg-success';
            case 'Inactive':
                return 'bg-danger';
            case 'Pending':
                return 'bg-warning';
            case 'Schedule':
                return 'bg-info';
            default:
                return 'bg-secondary';
        }
    }
}
