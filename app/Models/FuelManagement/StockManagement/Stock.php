<?php

namespace App\Models\FuelManagement\StockManagement;

use App\Models\CompanyProfile;
use App\Models\FuelManagement\FuelStation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory, SoftDeletes;

    public const PRODUCT_AGO = 'AGO';
    public const PRODUCT_PMS = 'PMS';

    protected $fillable = [
        'company_id',
        'station_id',
        'delivery_date',
        'brv_number',
        'driver_name',
        'driver_phone',
        'invoice_number',
        'product_type',
        'dispatched_quantity',
        'received_quantity',
        'inspected_by',
        'running_balance',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'station_id' => 'integer',
        'delivery_date' => 'date',
        'dispatched_quantity' => 'decimal:2',
        'received_quantity' => 'decimal:2',
        'running_balance' => 'decimal:2',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(FuelStation::class, 'station_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeForCompany(Builder $query, ?int $companyId): Builder
    {
        return $companyId ? $query->where('stocks.company_id', $companyId) : $query;
    }

    public function scopeForStation(Builder $query, ?int $stationId): Builder
    {
        return $stationId ? $query->where('stocks.station_id', $stationId) : $query;
    }

    public function scopeForProduct(Builder $query, ?string $productType): Builder
    {
        return $productType ? $query->where('stocks.product_type', $productType) : $query;
    }

    public function scopeForDateRange(Builder $query, ?string $startDate, ?string $endDate): Builder
    {
        if ($startDate) {
            $query->where('stocks.delivery_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('stocks.delivery_date', '<=', $endDate);
        }
        return $query;
    }

    public function scopeOrderByDate(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('stocks.delivery_date', $direction);
    }

    public function getQuantityDifferenceAttribute(): float
    {
        return (float) $this->received_quantity - (float) $this->dispatched_quantity;
    }

    public function getDifferencePercentageAttribute(): float
    {
        if ((float) $this->dispatched_quantity == 0) {
            return 0;
        }
        return round((($this->received_quantity - $this->dispatched_quantity) / $this->dispatched_quantity) * 100, 2);
    }
}
