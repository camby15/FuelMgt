<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CentralStore extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'central_store';

    protected $fillable = [
        'company_id',
        'supplier_id',
        'purchase_order_id',
        'item_name',
        'item_category',
        'brand',
        'unit',
        'description',
        'images',
        'sku',
        'barcode',
        'unit_price',
        'quantity',
        'total_price',
        'batch_number',
        'location',
        'status',
        'notes',
        'created_by',
        'transfer_date',
        'completed_date'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'transfer_date' => 'datetime',
        'completed_date' => 'datetime',
        'images' => 'array'
    ];

    /**
     * Get the company that owns the central store item
     */
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    /**
     * Get the supplier that owns the central store item
     */
    public function supplier()
    {
        return $this->belongsTo(Wh_Supplier::class, 'supplier_id');
    }

    /**
     * Get the purchase order that owns the central store item
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(Wh_PurchaseOrder::class, 'purchase_order_id');
    }

    /**
     * Get the user who created the central store item
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the quality inspections for this item
     */
    public function qualityInspection()
    {
        return $this->hasMany(QualityInspection::class, 'item_id');
    }

    /**
     * Scope a query to only include items for a specific company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope a query to only include items with a specific status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include pending items
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed items
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get the status badge class for display
     */
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'warning';
            case 'completed':
                return 'success';
            default:
                return 'secondary';
        }
    }

    /**
     * Get the formatted transfer date
     */
    public function getFormattedTransferDateAttribute()
    {
        return $this->transfer_date ? $this->transfer_date->format('M d, Y') : 'N/A';
    }

    /**
     * Get the formatted completed date
     */
    public function getFormattedCompletedDateAttribute()
    {
        return $this->completed_date ? $this->completed_date->format('M d, Y') : 'N/A';
    }

    /**
     * Check if the item can be marked as completed
     */
    public function canBeCompleted()
    {
        return $this->status === 'pending';
    }

    /**
     * Mark the item as completed
     */
    public function markAsCompleted()
    {
        if ($this->canBeCompleted()) {
            $this->update([
                'status' => 'completed',
                'completed_date' => now()
            ]);
            return true;
        }
        return false;
    }
}
