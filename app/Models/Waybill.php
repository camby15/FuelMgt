<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Waybill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'waybill_number',
        'company_id',
        'outbound_order_id',
        'requisition_id',
        'shipment_type',
        'total_weight',
        'total_packages',
        'total_value',
        'origin_name',
        'origin_address',
        'origin_contact',
        'origin_phone',
        'destination_name',
        'destination_address',
        'destination_contact',
        'destination_phone',
        'items',
        'packages',
        'transport_mode',
        'vehicle_number',
        'driver_name',
        'driver_phone',
        'carrier_company',
        'tracking_number',
        'status',
        'dispatch_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'delivered_to',
        'delivery_notes',
        'proof_of_delivery',
        'delivered_by',
        'special_instructions',
        'handling_instructions',
        'notes',
        'requires_signature',
        'fragile',
        'urgent',
        'shipping_cost',
        'insurance_value',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'items' => 'array',
        'packages' => 'array',
        'dispatch_date' => 'datetime',
        'expected_delivery_date' => 'datetime',
        'actual_delivery_date' => 'datetime',
        'requires_signature' => 'boolean',
        'fragile' => 'boolean',
        'urgent' => 'boolean',
        'total_weight' => 'decimal:2',
        'total_value' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'insurance_value' => 'decimal:2',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_IN_TRANSIT = 'in_transit';
    const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_RETURNED = 'returned';
    const STATUS_CANCELLED = 'cancelled';

    // Shipment type constants
    const TYPE_INTERNAL = 'internal';
    const TYPE_EXTERNAL = 'external';
    const TYPE_CUSTOMER = 'customer';

    // Transport mode constants
    const TRANSPORT_VEHICLE = 'vehicle';
    const TRANSPORT_COURIER = 'courier';
    const TRANSPORT_PICKUP = 'pickup';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($waybill) {
            if (empty($waybill->waybill_number)) {
                $waybill->waybill_number = 'WB-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    // Relationships
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function outboundOrder()
    {
        return $this->belongsTo(OutboundOrder::class, 'outbound_order_id');
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }

    public function deliveredBy()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', self::STATUS_IN_TRANSIT);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeUrgent($query)
    {
        return $query->where('urgent', true);
    }

    public function scopeFragile($query)
    {
        return $query->where('fragile', true);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_IN_TRANSIT => 'badge-info',
            self::STATUS_OUT_FOR_DELIVERY => 'badge-primary',
            self::STATUS_DELIVERED => 'badge-success',
            self::STATUS_RETURNED => 'badge-secondary',
            self::STATUS_CANCELLED => 'badge-danger',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getItemCountAttribute()
    {
        return is_array($this->items) ? count($this->items) : 0;
    }

    public function getEstimatedDeliveryDaysAttribute()
    {
        if (!$this->dispatch_date || !$this->expected_delivery_date) {
            return null;
        }

        return $this->dispatch_date->diffInDays($this->expected_delivery_date);
    }

    public function getIsOverdueAttribute()
    {
        return $this->expected_delivery_date && 
               $this->expected_delivery_date->isPast() && 
               !in_array($this->status, [self::STATUS_DELIVERED, self::STATUS_CANCELLED]);
    }

    // Helper methods
    public function canBeDispatched()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function canBeDelivered()
    {
        return in_array($this->status, [self::STATUS_IN_TRANSIT, self::STATUS_OUT_FOR_DELIVERY]);
    }

    public function canBeCancelled()
    {
        return !in_array($this->status, [self::STATUS_DELIVERED, self::STATUS_CANCELLED]);
    }

    // Static helper method to check if waybill can be created for a requisition
    public static function canCreateWaybillForRequisition($requisition)
    {
        // Allow waybill creation for fully approved requisitions
        if ($requisition->status === Requisition::STATUS_APPROVED) {
            return [
                'can_create' => true,
                'reason' => 'Requisition is fully approved',
                'available_items' => $requisition->items
            ];
        }
        
        // Allow waybill creation for partially approved requisitions
        // but only for the items that were approved
        if ($requisition->status === Requisition::STATUS_PARTIALLY_APPROVED) {
            // Get only the approved items (items with sufficient stock)
            $approvedItems = self::getApprovedItemsForPartiallyApprovedRequisition($requisition);
            
            if (!empty($approvedItems)) {
                return [
                    'can_create' => true,
                    'reason' => 'Requisition is partially approved - only approved items can be waybilled',
                    'available_items' => $approvedItems,
                    'is_partial' => true
                ];
            }
        }
        
        return [
            'can_create' => false,
            'reason' => 'Requisition is not approved or has no available items for waybill',
            'available_items' => []
        ];
    }

    // Helper method to get approved items from partially approved requisition
    private static function getApprovedItemsForPartiallyApprovedRequisition($requisition)
    {
        $approvedItems = [];
        
        if ($requisition->items && is_array($requisition->items)) {
            foreach ($requisition->items as $item) {
                $centralStoreItem = \App\Models\CentralStore::find($item['item_id']);
                if ($centralStoreItem) {
                    $requestedQuantity = (float) ($item['quantity'] ?? 0);
                    $availableQuantity = (float) $centralStoreItem->quantity;
                    
                    // For partially approved requisitions, we only waybill items that had sufficient stock
                    // (i.e., items where requested <= available)
                    if ($requestedQuantity <= $availableQuantity) {
                        $approvedItems[] = [
                            'item_id' => $item['item_id'],
                            'item_name' => $centralStoreItem->item_name,
                            'quantity' => $requestedQuantity, // Use requested quantity since it was fully satisfied
                            'unit_price' => $centralStoreItem->unit_price,
                            'total_price' => $requestedQuantity * $centralStoreItem->unit_price,
                            'batch_number' => $centralStoreItem->batch_number,
                            'category' => $centralStoreItem->item_category
                        ];
                    }
                    // For items with insufficient stock, we only waybill the available portion
                    elseif ($availableQuantity > 0) {
                        $approvedItems[] = [
                            'item_id' => $item['item_id'],
                            'item_name' => $centralStoreItem->item_name,
                            'quantity' => $availableQuantity, // Only the available portion
                            'unit_price' => $centralStoreItem->unit_price,
                            'total_price' => $availableQuantity * $centralStoreItem->unit_price,
                            'batch_number' => $centralStoreItem->batch_number,
                            'category' => $centralStoreItem->item_category,
                            'note' => 'Partial quantity - rest pending re-order'
                        ];
                    }
                }
            }
        }
        
        return $approvedItems;
    }

    public function markAsDispatched()
    {
        $this->update([
            'status' => self::STATUS_IN_TRANSIT,
            'dispatch_date' => now(),
        ]);
    }

    public function markAsDelivered($deliveredTo = null, $notes = null)
    {
        $this->update([
            'status' => self::STATUS_DELIVERED,
            'actual_delivery_date' => now(),
            'delivered_to' => $deliveredTo,
            'delivery_notes' => $notes,
            'delivered_by' => auth()->id(),
        ]);
    }
}