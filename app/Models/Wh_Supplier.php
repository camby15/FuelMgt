<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wh_Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
        
        // Company Information
        'company_name',
        'business_type',
        'tin',
        'vat_number',
        'ssnit_number',
        'year_established',
        'registration_number',
        'company_description',
        'business_sector',
        'company_size',
        
        // Contact Information
        'primary_contact',
        'contact_position',
        'job_title',
        'email',
        'phone',
        'whatsapp_number',
        'landline',
        'website',
        'social_media',
        
        // Ghana Specific Details
        'gipc_registration',
        'fdia_status',
        'ghanapost_address',
        'local_council',
        
        // Address Information
        'street_address',
        'area',
        'city',
        'region',
        'gps_address',
        'postal_code',
        
        // Additional Information
        'payment_terms',
        'currency',
        'status',
        'notes'
    ];

    protected $casts = [
        'year_established' => 'integer',
        'rating' => 'decimal:1'
    ];

    protected $appends = ['average_rating'];

    /**
     * Get the company that owns the supplier
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    /**
     * Get the user who created the supplier
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all ratings for this supplier
     */
     public function ratings(): HasMany
{
    return $this->hasMany(SupplierRating::class, 'supplier_id');
    //                         Tell Laravel to use this column ↑↑↑↑↑↑↑
}

    /**
     * Get the latest 3 ratings for this supplier with user info
     */
    public function latestRatings()
    {
        return $this->ratings()
            ->with('user:id,fullname')
            ->latest()
            ->take(3);
    }

    /**
     * Calculate average rating attribute
     */
    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function purchaseOrders(): HasMany
{
    return $this->hasMany(Wh_PurchaseOrder::class, 'supplier_id');
}

    /**
     * Get all warehouse logs for this supplier
     */
    public function logs()
    {
        return $this->morphMany(WarehouseLog::class, 'model');
    }
}