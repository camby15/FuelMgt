<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\POReceiving;

class Wh_PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;
   
    protected $fillable = [
        'company_id',
        'user_id',
        'po_number',
        'supplier_id',
        'order_date',
        'delivery_date',
        'status',
        'payment_terms',
        'notes',
        'requested_by',
        
        'created_by',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'total_value',
        'total_items',
       
        // Tax configuration fields
        'tax_configuration_id',
        'tax_type',
        'tax_rate',
        'subtotal',
        'tax_amount',
        'total_amount',
        'is_tax_exempt',
        'tax_exemption_reason',
        'tax_breakdown',
        
        // Reorder fields
        'is_reorder',
        'batch_number',
        'reorder_reason',
       
        'items',
    ];

    protected $casts = [
        'items' => 'array', // 👈 This will auto-convert JSON to array and back
        'tax_breakdown' => 'array',
        'is_tax_exempt' => 'boolean',
        'is_reorder' => 'boolean',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Wh_Supplier::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function taxConfiguration()
    {
        return $this->belongsTo(TaxConfiguration::class, 'tax_configuration_id');
    }


    // In App\Models\Wh_PurchaseOrder.php
public function qualityInspections()
{
    return $this->hasMany(QualityInspection::class, 'purchase_order_id');
}

    public function logs()
    {
        return $this->hasMany(WarehouseLog::class, 'purchase_order_id');
    }

    public function receivings()
    {
        return $this->hasMany(POReceiving::class, 'purchase_order_id');
    }

    public function invoices()
    {
        return $this->hasMany(POInvoice::class, 'purchase_order_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
