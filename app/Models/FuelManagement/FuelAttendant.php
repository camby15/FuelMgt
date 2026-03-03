<?php

namespace App\Models\FuelManagement;

use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FuelAttendant extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_ON_LEAVE = 'on_leave';

    protected $fillable = [
        'company_id',
        'fuel_station_id',
        'staff_id',
        'first_name',
        'other_names',
        'gender',
        'date_of_birth',
        'address',
        'phone_number_1',
        'phone_number_2',
        'contact_name',
        'contact_relationship',
        'contact_phone',
        'contact_address',
        'status',
        'shift',
        'profile_photo_path',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'fuel_station_id' => 'integer',
        'date_of_birth' => 'date',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $appends = [
        'full_name',
        'profile_photo_url',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(FuelStation::class, 'fuel_station_id');
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
        return $companyId ? $query->where('fuel_attendants.company_id', $companyId) : $query;
    }

    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->first_name,
            $this->other_names,
        ]);

        return $parts ? implode(' ', $parts) : $this->staff_id;
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (!$this->profile_photo_path) {
            return null;
        }

        if (Str::startsWith($this->profile_photo_path, ['http://', 'https://'])) {
            return $this->profile_photo_path;
        }

        return Storage::disk('public')->url($this->profile_photo_path);
    }
}
