<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class POReceiving extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'po_receivings';

    protected $fillable = [
        'company_id',
        'user_id',
        'purchase_order_id',
        'receiving_number',
        'receiving_date',
        'delivery_note',
        'vehicle_number',
        'notes',
        'received_items',
        'total_received',
        'total_rejected',
        'status'
    ];

    protected $casts = [
        'receiving_date' => 'date',
        'received_items' => 'array',
        'total_received' => 'decimal:2',
        'total_rejected' => 'decimal:2'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PARTIAL = 'partial';
    const STATUS_COMPLETED = 'completed';

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(Wh_PurchaseOrder::class);
    }

    /**
     * Get the total quantity received for a specific item
     */
    public function getItemReceivedQty($itemId)
    {
        $item = Arr::first($this->received_items, function ($item) use ($itemId) {
            return ($item['item_id'] ?? null) == $itemId;
        });

        return $item['received_qty'] ?? 0;
    }

    /**
     * Get the total quantity rejected for a specific item
     */
    public function getItemRejectedQty($itemId)
    {
        $item = Arr::first($this->received_items, function ($item) use ($itemId) {
            return ($item['item_id'] ?? null) == $itemId;
        });

        return $item['rejected_qty'] ?? 0;
    }

    /**
     * Get all items with their received status
     */
    public function getItemsWithStatus()
    {
        if (!$this->purchaseOrder) {
            return [];
        }

        return collect($this->purchaseOrder->items)->map(function ($poItem) {
            return [
                'item_id' => $poItem['id'] ?? null,
                'name' => $poItem['name'],
                'ordered_qty' => $poItem['quantity'],
                'received_qty' => $this->getItemReceivedQty($poItem['id'] ?? $poItem['name']),
                'rejected_qty' => $this->getItemRejectedQty($poItem['id'] ?? $poItem['name']),
                'unit' => $poItem['unit'] ?? 'pcs',
                'unit_price' => $poItem['unit_price'] ?? 0,
                'location' => $this->getItemLocation($poItem['id'] ?? $poItem['name'])
            ];
        });
    }

    /**
     * Get the storage location for a specific item
     */
    public function getItemLocation($itemId)
    {
        $item = Arr::first($this->received_items, function ($item) use ($itemId) {
            return ($item['item_id'] ?? null) == $itemId;
        });

        return $item['location'] ?? 'Main Warehouse';
    }

    /**
     * Scope for completed receivings
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for pending receivings
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for partial receivings
     */
    public function scopePartial($query)
    {
        return $query->where('status', self::STATUS_PARTIAL);
    }

    /**
     * Check if receiving is completed
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if receiving is pending
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if receiving is partial
     */
    public function isPartial()
    {
        return $this->status === self::STATUS_PARTIAL;
    }
}