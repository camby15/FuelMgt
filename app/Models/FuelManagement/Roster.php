<?php

namespace App\Models\FuelManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Roster extends Model
{
    use HasFactory;

    protected $table = 'rosters';

    protected $fillable = [
        'company_id',
        'station_id',
        'attendant_id',
        'week_start_date',
        'day_of_week',
        'shift_type',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'week_start_date' => 'date',
        'day_of_week' => 'integer',
    ];

    public function station(): BelongsTo
    {
        return $this->belongsTo(FuelStation::class, 'station_id');
    }

    public function attendant(): BelongsTo
    {
        return $this->belongsTo(FuelAttendant::class, 'attendant_id');
    }

    public function scopeForCompany($query, ?int $companyId)
    {
        return $companyId ? $query->where('rosters.company_id', $companyId) : $query;
    }

    public function scopeForStation($query, ?int $stationId)
    {
        return $stationId ? $query->where('rosters.station_id', $stationId) : $query;
    }

    public function scopeForWeek($query, string $weekStartDate)
    {
        return $query->where('week_start_date', $weekStartDate);
    }

    public function scopeForDay($query, int $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    public function scopeForShiftType($query, string $shiftType)
    {
        return $query->where('shift_type', $shiftType);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
