<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QualityInspection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'inspection_id',
        'company_id',
        'user_id',
        'supplier_id',
        'purchase_order_id',
        'item_id',
        'item_name',
        'item_category',
        'unit_price',
        'quantity',
        'total_price',
        'batch_number',
        'inspection_date',
        'checklist_results',
        'notes',
        'status',
        'photos',
        'inspector_name',
        'inspection_result'
    ];

    protected $casts = [
        'checklist_results' => 'array',
        'photos' => 'array',
        'inspection_date' => 'date',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Generate inspection ID on creation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->inspection_id = 'INSP-' . strtoupper(uniqid());
            $model->batch_number = 'BATCH-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        });
    }

    // Relationships
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Wh_Supplier::class, 'supplier_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(Wh_PurchaseOrder::class, 'purchase_order_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}