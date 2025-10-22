<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';

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
        'sub_categories',
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
        'sub_categories' => 'array',
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
     * Get the company that owns the category.
     */
    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    /**
     * Get the head of category name.
     */
    public function getHeadOfCategoryAttribute()
    {
        return $this->head_name;
    }


    /**
     * Get the user who created this category.
     */
    public function creator()
    {
        return $this->belongsTo(CompanySubUser::class, 'created_by');
    }

    /**
     * Get the user who last updated this category.
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
     * Scope a query to only include categories for the current company.
     */
    public function scopeForCurrentCompany(Builder $query): Builder
    {
        $companyId = Session::get('selected_company_id');
        
        // If no company ID in session, try to get it from authenticated user
        if (!$companyId) {
            if (auth('company_sub_user')->check()) {
                $companyId = auth('company_sub_user')->user()->company_id;
                Session::put('selected_company_id', $companyId);
            } elseif (auth('sub_user')->check()) {
                $companyId = auth('sub_user')->user()->company_id;
                Session::put('selected_company_id', $companyId);
            } elseif (auth()->check() && auth()->user()->companyProfile) {
                $companyId = auth()->id();
                Session::put('selected_company_id', $companyId);
            }
        }
        
        if ($companyId) {
            return $query->where('company_id', $companyId);
        }
        
        // In development/testing, return all categories if no company context
        if (config('app.env') === 'local' || config('app.debug')) {
            return $query;
        }
        
        return $query->whereNull('id'); // Return empty result if no company selected
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive categories.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope to order categories by name.
     */
    public function scopeOrderByName(Builder $query): Builder
    {
        return $query->orderBy('name', 'asc');
    }

    /**
     * Scope to order categories by sort order.
     */
    public function scopeOrderBySortOrder(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Get the sub categories as a formatted string.
     */
    public function getSubCategoriesStringAttribute(): string
    {
        if (empty($this->sub_categories)) {
            return 'No sub categories';
        }

        return implode(', ', $this->sub_categories);
    }

    /**
     * Get the sub categories count.
     */
    public function getSubCategoriesCountAttribute(): int
    {
        return is_array($this->sub_categories) ? count($this->sub_categories) : 0;
    }

    /**
     * Check if category has sub categories.
     */
    public function hasSubCategories(): bool
    {
        return !empty($this->sub_categories) && count($this->sub_categories) > 0;
    }

    /**
     * Add a sub category.
     */
    public function addSubCategory(string $subCategoryName): void
    {
        $subCategories = $this->sub_categories ?? [];
        
        if (!in_array($subCategoryName, $subCategories)) {
            $subCategories[] = $subCategoryName;
            $this->sub_categories = $subCategories;
        }
    }

    /**
     * Remove a sub category.
     */
    public function removeSubCategory(string $subCategoryName): void
    {
        $subCategories = $this->sub_categories ?? [];
        
        $index = array_search($subCategoryName, $subCategories);
        if ($index !== false) {
            unset($subCategories[$index]);
            $this->sub_categories = array_values($subCategories); // Re-index array
        }
    }

    /**
     * Generate unique category code.
     */
    public static function generateUniqueCode(string $prefix = 'CAT'): string
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
     * Get category statistics for current company.
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
                'with_sub_categories' => 0,
            ];
        }

        $query = self::where('company_id', $companyId);
        
        return [
            'total' => $query->count(),
            'active' => $query->where('status', 'active')->count(),
            'inactive' => $query->where('status', 'inactive')->count(),
            'with_heads' => $query->whereNotNull('head_name')->where('head_name', '!=', '')->count(),
            'with_sub_categories' => $query->whereNotNull('sub_categories')
                ->where('sub_categories', '!=', '[]')
                ->count(),
        ];
    }

    /**
     * Search categories by name or code.
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
