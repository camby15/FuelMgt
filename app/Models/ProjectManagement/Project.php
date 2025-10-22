<?php

namespace App\Models\ProjectManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\CompanySubUser;
use App\Models\CompanyProfile;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    // Status Constants
    public const STATUS_NOT_STARTED = 'not_started';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_ON_HOLD = 'on_hold';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'project_type', // CHANGED: Now a string field instead of project_type_id
        'description',
        'project_manager_id',
        'start_date',
        'end_date',
        'budget',
        'status',
        'progress',
        'client_name',
        'client_email',
        'client_phone',
        'project_code',
        'actual_cost',
        'notes',
        'objectives',
        'deliverables',
        'client_id'
    ];

    /**
     * The statuses that are available for a project.
     *
     * @var array
     */
    public static $statuses = [
        'not_started' => 'Not Started', // UPDATED: Fixed to match constants
        'in_progress' => 'In Progress',
        'on_hold' => 'On Hold',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'actual_cost' => 'decimal:2', // ADDED: Cast for actual_cost
        'progress' => 'integer',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_NOT_STARTED,
        'progress' => 0,
        'actual_cost' => 0,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'remaining_budget',
        'is_overdue',
        'display_name',
        'project_type_name', // ADDED: For easy access to project type name
        'manager_name', // ADDED: For easy access to manager name
        'status_color', // ADDED: For UI status colors
        'formatted_budget', // ADDED: For formatted budget display
        'formatted_actual_cost' // ADDED: For formatted actual cost display
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($project) {
            if (empty($project->project_code)) {
                // ENHANCED: Better project code generation with company scoping
                $project->project_code = self::generateProjectCode($project->company_id);
            }
        });
    }

    /**
     * ADDED: Get the company that owns the project.
     */
    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    // REMOVED: projectType relationship since we're now using project_type as string

    /**
     * The project manager (sub user) associated with the project.
     */
    public function manager(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CompanySubUser::class, 'project_manager_id', 'id');
    }
    
    /**
     * Set the manager_id attribute (maps to project_manager_id in database).
     *
     * @param  mixed  $value
     * @return void
     */
    public function setManagerIdAttribute($value)
    {
        $this->attributes['project_manager_id'] = $value;
    }

    /**
     * Get the manager_id attribute (from project_manager_id in database).
     * This maintains backward compatibility with existing code.
     *
     * @return mixed
     */
    public function getManagerIdAttribute()
    {
        return $this->attributes['project_manager_id'] ?? null;
    }

    // REMOVED: Type attribute methods since we're now using project_type as string

    /**
     * Get the client associated with the project.
     * Uncomment when clients table is available
     */
    // public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    // {
    //     return $this->belongsTo(Client::class);
    // }

    /**
     * Get validation rules for the model.
     *
     * @param int|null $id
     * @return array
     */
    public static function rules($id = null): array
    {
        return [
            'company_id' => 'required|exists:company_profiles,id', // ADDED: Company validation
            'name' => 'required|string|max:255',
            'project_code' => 'required|string|unique:projects,project_code,' . $id,
            'description' => 'nullable|string',
            'project_type' => 'required|string|max:255', // CHANGED: Now a string field
            'project_manager_id' => 'nullable|exists:company_sub_users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'required|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:' . implode(',', [
                self::STATUS_NOT_STARTED,
                self::STATUS_IN_PROGRESS,
                self::STATUS_ON_HOLD,
                self::STATUS_CANCELLED,
                self::STATUS_COMPLETED
            ]),
            'progress' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string',
            'objectives' => 'nullable|string',
            'deliverables' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id'
        ];
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeStatus($query, string $status): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', $status);
    }

    /**
     * ADDED: Scope a query to filter by project type.
     */
    public function scopeByType($query, string $type): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('project_type', $type);
    }

    /**
     * ADDED: Scope a query to only include active projects.
     */
    public function scopeActive($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereIn('status', [self::STATUS_NOT_STARTED, self::STATUS_IN_PROGRESS]);
    }

    /**
     * Scope a query to only include projects in progress.
     */
    public function scopeInProgress($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    /**
     * Scope a query to only include completed projects.
     */
    public function scopeCompleted($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Calculate the project's remaining budget.
     */
    public function getRemainingBudgetAttribute(): float
    {
        return (float) bcsub($this->budget, $this->actual_cost, 2);
    }

    /**
     * Check if the project is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->end_date->isPast() && $this->status !== self::STATUS_COMPLETED;
    }

    /**
     * Get the display name for the project.
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->project_code} - {$this->name}";
    }

    /**
     * ADDED: Get the project type name.
     */
    public function getProjectTypeNameAttribute(): string
    {
        return $this->project_type ?? 'No Type';
    }

    /**
     * ADDED: Get the manager name.
     */
    public function getManagerNameAttribute(): string
    {
        return $this->manager ? ($this->manager->fullname ?? $this->manager->name ?? 'Unassigned') : 'Unassigned';
    }

    /**
     * ADDED: Get the project status color for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_NOT_STARTED => 'secondary',
            self::STATUS_IN_PROGRESS => $this->progress > 70 ? 'success' : ($this->progress > 30 ? 'primary' : 'warning'),
            self::STATUS_ON_HOLD => 'warning',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
            default => 'secondary'
        };
    }

    /**
     * ADDED: Get formatted budget.
     */
    public function getFormattedBudgetAttribute(): string
    {
        return '$' . number_format($this->budget, 0);
    }

    /**
     * ADDED: Get formatted actual cost.
     */
    public function getFormattedActualCostAttribute(): string
    {
        return '$' . number_format($this->actual_cost, 0);
    }

    /**
     * ADDED: Check if the project is on track.
     */
    public function isOnTrack(): bool
    {
        if (!$this->start_date || !$this->end_date) {
            return true;
        }

        $totalDays = $this->start_date->diffInDays($this->end_date);
        $elapsedDays = $this->start_date->diffInDays(now());
        
        if ($totalDays <= 0) {
            return true;
        }
        
        $expectedProgress = ($elapsedDays / $totalDays) * 100;
        return $this->progress >= $expectedProgress;
    }

    /**
     * ADDED: Get the project's duration in days.
     */
    public function getDurationDays(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * ADDED: Get the remaining days for the project.
     */
    public function getRemainingDays(): int
    {
        if (!$this->end_date) {
            return 0;
        }

        return max(0, now()->diffInDays($this->end_date, false));
    }

    /**
     * ADDED: Get the budget utilization percentage.
     */
    public function getBudgetUtilization(): float
    {
        if ($this->budget <= 0) {
            return 0;
        }

        return ($this->actual_cost / $this->budget) * 100;
    }

    /**
     * Get all status options.
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_NOT_STARTED => 'Not Started',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_ON_HOLD => 'On Hold',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_COMPLETED => 'Completed',
        ];
    }

    /**
     * ENHANCED: Generate a unique project code with company scoping.
     *
     * @param int $companyId
     * @return string
     */
    public static function generateProjectCode($companyId): string
    {
        $prefix = 'PRJ';
        $year = date('Y');
        
        // Get the last project code for this company and year
        $lastProject = self::where('company_id', $companyId)
            ->where('project_code', 'LIKE', "{$prefix}-{$year}-%")
            ->orderBy('project_code', 'desc')
            ->first();

        if ($lastProject) {
            // Extract the number from the last project code
            $lastNumber = (int) substr($lastProject->project_code, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $year, $newNumber);
    }

    // REMOVED: getAvailableTypes method since we're no longer using ProjectType model
}