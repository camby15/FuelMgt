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

class StockReconciliation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'station_id',
        'recon_date',
        'tank',
        'opening_stock',
        'add_stock',
        'total_stock',
        'sales_volume',
        'closing_stock',
        'dipping_reading',
        'variance',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'station_id' => 'integer',
        'recon_date' => 'date',
        'opening_stock' => 'decimal:2',
        'add_stock' => 'decimal:2',
        'total_stock' => 'decimal:2',
        'sales_volume' => 'decimal:2',
        'closing_stock' => 'decimal:2',
        'dipping_reading' => 'decimal:2',
        'variance' => 'decimal:2',
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
        return $companyId ? $query->where('stock_reconciliations.company_id', $companyId) : $query;
    }

    public function scopeForStation(Builder $query, ?int $stationId): Builder
    {
        return $stationId ? $query->where('stock_reconciliations.station_id', $stationId) : $query;
    }

    public function scopeForDateRange(Builder $query, ?string $startDate, ?string $endDate): Builder
    {
        if ($startDate) {
            $query->where('stock_reconciliations.recon_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('stock_reconciliations.recon_date', '<=', $endDate);
        }

        return $query;
    }

    public function scopeForTank(Builder $query, ?string $tank): Builder
    {
        return $tank ? $query->where('stock_reconciliations.tank', $tank) : $query;
    }

    public function scopeOrderByDate(Builder $query, string $direction = 'desc'): Builder
    {
        return $query->orderBy('stock_reconciliations.recon_date', $direction);
    }
}

