<?php

namespace App\Models\FuelManagement;

use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StationManager extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_TERMINATED = 'terminated';

    protected $fillable = [
        'company_id',
        'fuel_station_id',
        'full_name',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'avatar_path',
        'address',
        'location',
        'assigned_at',
        'status',
        'termination_reason',
        'terminated_at',
        'terminated_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'fuel_station_id' => 'integer',
        'date_of_birth' => 'date',
        'assigned_at' => 'date',
        'terminated_at' => 'datetime',
        'terminated_by' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function station()
    {
        return $this->belongsTo(FuelStation::class, 'fuel_station_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function terminator()
    {
        return $this->belongsTo(User::class, 'terminated_by');
    }

    public function scopeForCompany(Builder $query, ?int $companyId): Builder
    {
        return $companyId ? $query->where('company_id', $companyId) : $query;
    }
}
