<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

class DepartmentCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'department_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'company_id',
        'head_name',
        'status',
        'color',
        'sub_departments',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sub_departments' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Get the company that owns the department.
     */
    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    /**
     * Get the head of department name.
     */
    public function getHeadOfDepartmentAttribute()
    {
        return $this->head_name;
    }

    /**
     * Get the user who created this department.
     */
    public function creator()
    {
        return $this->belongsTo(CompanySubUser::class, 'created_by');
    }

    /**
     * Get the user who last updated this department.
     */
    public function updater()
    {
        return $this->belongsTo(CompanySubUser::class, 'updated_by');
    }

    /**
     * Get the creator as a user from the default auth guard (fallback).
     */
    public function creatorUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater as a user from the default auth guard (fallback).
     */
    public function updaterUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include departments for the current company.
     */
    public function scopeForCurrentCompany(Builder $query): Builder
    {
        $companyId = Session::get('selected_company_id');
        
        if ($companyId) {
            return $query->where('company_id', $companyId);
        }
        
        return $query->whereNull('id'); // Return empty result if no company selected
    }

    /**
     * Scope a query to only include active departments.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive departments.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope to order departments by name.
     */
    public function scopeOrderByName(Builder $query): Builder
    {
        return $query->orderBy('name', 'asc');
    }

    /**
     * Scope to order departments by sort order.
     */
    public function scopeOrderBySortOrder(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Get the sub departments as a formatted string.
     */
    public function getSubDepartmentsStringAttribute(): string
    {
        if (empty($this->sub_departments)) {
            return 'No sub departments';
        }

        return implode(', ', $this->sub_departments);
    }

    /**
     * Get the sub departments count.
     */
    public function getSubDepartmentsCountAttribute(): int
    {
        return is_array($this->sub_departments) ? count($this->sub_departments) : 0;
    }

    /**
     * Check if department has sub departments.
     */
    public function hasSubDepartments(): bool
    {
        return !empty($this->sub_departments) && count($this->sub_departments) > 0;
    }

    /**
     * Add a sub department.
     */
    public function addSubDepartment(string $subDepartmentName): void
    {
        $subDepartments = $this->sub_departments ?? [];
        
        if (!in_array($subDepartmentName, $subDepartments)) {
            $subDepartments[] = $subDepartmentName;
            $this->sub_departments = $subDepartments;
        }
    }

    /**
     * Remove a sub department.
     */
    public function removeSubDepartment(string $subDepartmentName): void
    {
        $subDepartments = $this->sub_departments ?? [];
        
        $index = array_search($subDepartmentName, $subDepartments);
        if ($index !== false) {
            unset($subDepartments[$index]);
            $this->sub_departments = array_values($subDepartments); // Re-index array
        }
    }

    /**
     * Generate unique department code.
     */
    public static function generateUniqueCode(string $prefix = 'DEPT'): string
    {
        $companyId = Session::get('selected_company_id');
        
        do {
            $code = $prefix . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $exists = self::where('code', $code)
                ->where('company_id', $companyId)
                ->exists();
        } while ($exists);
        
        return $code;
    }

    /**
     * Get department statistics for current company.
     */
    public static function getStats(): array
    {
        $companyId = Session::get('selected_company_id');
        
        if (!$companyId) {
            return [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'with_heads' => 0,
                'with_sub_departments' => 0,
            ];
        }

        $query = self::where('company_id', $companyId);
        
        return [
            'total' => $query->count(),
            'active' => $query->where('status', 'active')->count(),
            'inactive' => $query->where('status', 'inactive')->count(),
            'with_heads' => $query->whereNotNull('head_name')->where('head_name', '!=', '')->count(),
            'with_sub_departments' => $query->whereNotNull('sub_departments')
                ->where('sub_departments', '!=', '[]')
                ->count(),
        ];
    }

    /**
     * Search departments by name or code.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('code', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%");
        });
    }


    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-set company_id and created_by when creating
        static::creating(function ($model) {
            if (!$model->company_id) {
                $model->company_id = Session::get('selected_company_id');
            }
            
            // Set created_by only for sub_user or company_sub_user (not regular users)
            // This prevents foreign key constraint violations
            if (!$model->created_by) {
                if (auth('sub_user')->check()) {
                    $model->created_by = auth('sub_user')->id();
                } elseif (auth('company_sub_user')->check()) {
                    $model->created_by = auth('company_sub_user')->id();
                }
                // Leave null for regular users to avoid foreign key constraint issues
            }
        });

        // Auto-set updated_by when updating
        static::updating(function ($model) {
            // Set updated_by only for sub_user or company_sub_user (not regular users)
            if (auth('sub_user')->check()) {
                $model->updated_by = auth('sub_user')->id();
            } elseif (auth('company_sub_user')->check()) {
                $model->updated_by = auth('company_sub_user')->id();
            }
            // Leave unchanged for regular users to avoid foreign key constraint issues
        });
    }
}