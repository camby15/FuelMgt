<?php

namespace App\Models\ProjectManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\CompanyProfile;
use App\Models\TeamParing;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    // Status Constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_ON_HOLD = 'on_hold';

    // Priority Constants
    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'title',
        'description',
        'task_code',
        'project_id',
        'assigned_team_id',
        'due_date',
        'start_date',
        'completed_date',
        'priority',
        'status',
        'progress',
        'notes',
        'attachments'
    ];

    /**
     * The statuses that are available for a task.
     *
     * @var array
     */
    public static $statuses = [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'on_hold' => 'On Hold'
    ];

    /**
     * The priorities that are available for a task.
     *
     * @var array
     */
    public static $priorities = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date',
        'start_date' => 'date',
        'completed_date' => 'date',
        'progress' => 'integer',
        'attachments' => 'array',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_PENDING,
        'priority' => self::PRIORITY_MEDIUM,
        'progress' => 0,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_overdue',
        'display_name',
        'status_color',
        'priority_color',
        'formatted_due_date',
        'team_name',
        'project_name'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            if (empty($task->task_code)) {
                $task->task_code = $task->generateTaskCode();
            }
        });
    }

    /**
     * Generate a unique task code.
     */
    public function generateTaskCode()
    {
        $prefix = 'TASK';
        $companyId = $this->company_id ?? session('selected_company_id');
        
        do {
            $number = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $code = $prefix . '-' . $number;
        } while (static::where('task_code', $code)->exists());

        return $code;
    }

    /**
     * Get the company that owns the task.
     */
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    /**
     * Get the project that the task belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get the team assigned to the task.
     */
    public function assignedTeam()
    {
        return $this->belongsTo(TeamParing::class, 'assigned_team_id');
    }

    /**
     * Check if the task is overdue.
     */
    public function getIsOverdueAttribute()
    {
        return $this->due_date && $this->due_date < now() && $this->status !== self::STATUS_COMPLETED;
    }

    /**
     * Get the display name for the task.
     */
    public function getDisplayNameAttribute()
    {
        return $this->title;
    }

    /**
     * Get the status color for UI display.
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'bg-warning',
            'in_progress' => 'bg-primary',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
            'on_hold' => 'bg-secondary'
        ];

        return $colors[$this->status] ?? 'bg-secondary';
    }

    /**
     * Get the priority color for UI display.
     */
    public function getPriorityColorAttribute()
    {
        $colors = [
            'low' => 'bg-success',
            'medium' => 'bg-warning',
            'high' => 'bg-danger'
        ];

        return $colors[$this->priority] ?? 'bg-secondary';
    }

    /**
     * Get formatted due date.
     */
    public function getFormattedDueDateAttribute()
    {
        return $this->due_date ? $this->due_date->format('M d, Y') : 'N/A';
    }

    /**
     * Get the team name.
     */
    public function getTeamNameAttribute()
    {
        return $this->assignedTeam ? $this->assignedTeam->team_name : 'No Team Assigned';
    }

    /**
     * Get the project name.
     */
    public function getProjectNameAttribute()
    {
        return $this->project ? $this->project->name : 'No Project';
    }

    /**
     * Scope tasks by company.
     */
    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope tasks by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope tasks by priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope overdue tasks.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', '!=', self::STATUS_COMPLETED);
    }

    /**
     * Get validation rules for the model.
     */
    public static function rules($taskId = null)
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_team_id' => 'required|exists:team_pairing,id',
            'due_date' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled,on_hold',
            'progress' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ];
    }
}
