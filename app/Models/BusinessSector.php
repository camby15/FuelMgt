<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

class BusinessSector extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'head_name',
        'status',
        'sub_sectors',
        'sort_order',
        'company_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'sub_sectors' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $attributes = [
        'status' => 'active',
        'sort_order' => 0
    ];

    /**
     * Get the status badge class attribute.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status === 'active' ? 'bg-success' : 'bg-danger';
    }

    /**
     * Get the status label attribute.
     */
    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    /**
     * Scope to filter by current company
     */
    public function scopeForCurrentCompany(Builder $query): Builder
    {
        $companyId = Session::get('selected_company_id');
        
        // If no company ID in session, try to get it from authenticated user
        if (!$companyId) {
            if (Auth::guard('company_sub_user')->check()) {
                $subUser = Auth::guard('company_sub_user')->user();
                $companyId = $subUser->company_id;
                Session::put('selected_company_id', $companyId);
            } elseif (Auth::guard('sub_user')->check()) {
                $subUser = Auth::guard('sub_user')->user();
                $companyId = $subUser->company_id;
                Session::put('selected_company_id', $companyId);
            } elseif (Auth::check()) {
                $user = Auth::user();
                if ($user->companyProfile) {
                    $companyId = $user->id;
                    Session::put('selected_company_id', $companyId);
                }
            }
        }
        
        if ($companyId) {
            return $query->where('company_id', $companyId);
        }
        
        // Return all business sectors in dev/debug if no company context
        if (config('app.env') === 'local' || config('app.debug')) {
            return $query; // Return all business sectors in dev/debug if no company context
        }
        
        return $query->whereNull('id'); // Return empty result if no company selected
    }

    /**
     * Scope to filter by status
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to filter by inactive status
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Relationship with creator user
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship with updater user
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relationship with company
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get formatted sub-sectors
     */
    public function getFormattedSubSectorsAttribute()
    {
        if (!$this->sub_sectors || empty($this->sub_sectors)) {
            return [];
        }
        
        if (is_string($this->sub_sectors)) {
            return json_decode($this->sub_sectors, true) ?: [];
        }
        
        return $this->sub_sectors;
    }

    /**
     * Get sub-sectors count
     */
    public function getSubSectorsCountAttribute()
    {
        return count($this->formatted_sub_sectors);
    }

    /**
     * Check if business sector has sub-sectors
     */
    public function hasSubSectors()
    {
        return $this->sub_sectors_count > 0;
    }


    /**
     * Boot method to set default values
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($businessSector) {
            if (!$businessSector->company_id) {
                $businessSector->company_id = Session::get('selected_company_id');
            }
            
            if (!$businessSector->created_by) {
                $businessSector->created_by = auth()->id();
            }
            
            if (!$businessSector->updated_by) {
                $businessSector->updated_by = auth()->id();
            }
            
            if (!$businessSector->sort_order) {
                $maxSortOrder = static::where('company_id', $businessSector->company_id)->max('sort_order') ?? 0;
                $businessSector->sort_order = $maxSortOrder + 1;
            }
        });

        static::updating(function ($businessSector) {
            $businessSector->updated_by = auth()->id();
        });
    }

    /**
     * Search scope
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('head_name', 'like', "%{$search}%");
        });
    }

    /**
     * Get business sector statistics
     */
    public static function getStats($companyId = null)
    {
        $query = static::query();
        
        if ($companyId) {
            $query->where('company_id', $companyId);
        }
        
        return $query->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN head_name IS NOT NULL AND head_name != "" THEN 1 ELSE 0 END) as with_heads,
            SUM(CASE WHEN sub_sectors IS NOT NULL AND sub_sectors != "[]" AND sub_sectors != "" THEN 1 ELSE 0 END) as with_sub_sectors
        ')->first();
    }
}
