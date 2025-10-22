<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseLog extends Model
{
    protected $fillable = [
         'company_id',
        'user_id',
        'purchase_order_id',
        'model',
        'model_id',
        'action',
        'description',
        'performed_by',
        'performed_at',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(Wh_PurchaseOrder::class, 'purchase_order_id');
    }
}

