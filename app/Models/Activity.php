<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
        'title',
        'description_agenda_notes',
        'call_event_type',
        'contact',
        'type',
        'status',
        'related_to',
        'related_to_type',
        'participants',
        'start_date',
        'end_date',
        'priority',
        'additional_data',
        'all_day',
        'duration'
        
    ];

    protected $casts = [
        'additional_data' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'all_day' => 'boolean'
    ];

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function relatedModel()
    {
        if ($this->related_to && $this->related_to_type) {
            $modelClass = 'App\Models\\' . $this->related_to_type;
            if (class_exists($modelClass)) {
                // Try to find the related model by ID first
                if (is_numeric($this->related_to)) {
                    return $this->belongsTo($modelClass, 'related_to');
                }
                
                // If not numeric, try to find by name
                return $this->belongsTo($modelClass, 'related_to')
                    ->where('name', $this->related_to);
            }
        }
        // Return a relationship to User model as a fallback
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
