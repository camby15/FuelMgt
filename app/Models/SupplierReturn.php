<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Wh_Supplier;

class SupplierReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplier_returns';

    protected $fillable = [
        'company_id',
        'user_id',
        'supplier_id',
        'purchase_order_id',
        'return_number',
        'return_date',
        'return_reason',
        'return_description',
        'return_items',
        'total_value',
        'status',
        'processed_at',
        'processed_by'
    ];

    protected $casts = [
        'return_date' => 'date',
        'return_items' => 'array'
    ];

    /**
     * Get the company that owns the return
     */
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    /**
     * Get the user who created the return
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the purchase order associated with the return
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(Wh_PurchaseOrder::class, 'purchase_order_id');
    }

    /**
     * Get the supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Wh_Supplier::class, 'supplier_id');
    }
}
