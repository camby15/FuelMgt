<?php

namespace App\Models\FuelManagement;

use App\Models\CompanyProfile;
use App\Models\FuelManagement\StationManager;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuelStation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'product',
        'products',
        'location',
        'gps_coordinates',
        'address',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'products' => 'array',
    ];

    protected $appends = [
        'latitude',
        'longitude',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function stationManagers(): HasMany
    {
        return $this->hasMany(StationManager::class, 'fuel_station_id');
    }

    public function activeManager(): HasOne
    {
        return $this->hasOne(StationManager::class, 'fuel_station_id')
            ->where('status', StationManager::STATUS_ACTIVE)
            ->latestOfMany('assigned_at');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeForCompany(Builder $query, ?int $companyId): Builder
    {
        return $companyId ? $query->where('fuel_stations.company_id', $companyId) : $query;
    }

    public function getLatitudeAttribute(): ?float
    {
        if (!$this->gps_coordinates) {
            return null;
        }

        $parts = array_map('trim', explode(',', $this->gps_coordinates));

        if (!isset($parts[0]) || !is_numeric($parts[0])) {
            return null;
        }

        return (float) $parts[0];
    }

    public function getLongitudeAttribute(): ?float
    {
        if (!$this->gps_coordinates) {
            return null;
        }

        $parts = array_map('trim', explode(',', $this->gps_coordinates));

        if (!isset($parts[1]) || !is_numeric($parts[1])) {
            return null;
        }

        return (float) $parts[1];
    }
}
