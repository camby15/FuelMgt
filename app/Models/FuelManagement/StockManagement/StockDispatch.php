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

class StockDispatch extends Model
{
    use HasFactory, SoftDeletes;

    public const PRODUCT_AGO = 'AGO';
    public const PRODUCT_PMS = 'PMS';

    protected $fillable = [
        'company_id',
        'station_id',
        'dispatch_date',
        'product_type',
        'depot',
        'bdc',
        'quantity_dispatched',
        'brv_number',
        'driver_name',
        'driver_phone',
        'inspected_by',
        'invoice_number',
        'waybill_path',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'station_id' => 'integer',
        'dispatch_date' => 'date',
        'quantity_dispatched' => 'decimal:2',
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
        return $companyId ? $query->where('stock_dispatches.company_id', $companyId) : $query;
    }

    public function scopeForStation(Builder $query, ?int $stationId): Builder
    {
        return $stationId ? $query->where('stock_dispatches.station_id', $stationId) : $query;
    }

    public function scopeForProduct(Builder $query, ?string $productType): Builder
    {
        return $productType ? $query->where('stock_dispatches.product_type', $productType) : $query;
    }

    public function scopeForDateRange(Builder $query, ?string $startDate, ?string $endDate): Builder
    {
        if ($startDate) {
            $query->where('stock_dispatches.dispatch_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('stock_dispatches.dispatch_date', '<=', $endDate);
        }
        return $query;
    }

    public function scopeOrderByDate(Builder $query, string $direction = 'desc'): Builder
    {
        return $query->orderBy('stock_dispatches.dispatch_date', $direction);
    }
}
