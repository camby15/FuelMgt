<?php

namespace App\Models\ProjectManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\CompanyProfile;
use App\Models\TeamParing;

class PmReport extends Model
{
    use HasFactory, SoftDeletes;

    // Report Type Constants
    public const TYPE_ANALYTICS = 'analytics';
    public const TYPE_SUMMARY = 'summary';
    public const TYPE_DETAILED = 'detailed';

    // Period Type Constants
    public const PERIOD_DAILY = 'daily';
    public const PERIOD_WEEKLY = 'weekly';
    public const PERIOD_MONTHLY = 'monthly';
    public const PERIOD_QUARTERLY = 'quarterly';
    public const PERIOD_YEARLY = 'yearly';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pmreports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'report_name',
        'report_type',
        'report_date',
        'period_type',
        'total_projects',
        'active_projects',
        'completed_projects',
        'on_hold_projects',
        'cancelled_projects',
        'total_tasks',
        'active_tasks',
        'completed_tasks',
        'pending_tasks',
        'overdue_tasks',
        'high_priority_tasks',
        'medium_priority_tasks',
        'low_priority_tasks',
        'total_budget',
        'budget_spent',
        'budget_remaining',
        'budget_utilization_percentage',
        'total_team_members',
        'active_team_members',
        'team_members_on_leave',
        'team_members_available',
        'project_completion_rate',
        'task_completion_rate',
        'on_time_delivery_rate',
        'average_project_duration_days',
        'average_task_duration_days',
        'timeline_data',
        'status_distribution',
        'priority_distribution',
        'notes',
        'is_active',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'report_date' => 'date',
        'total_budget' => 'decimal:2',
        'budget_spent' => 'decimal:2',
        'budget_remaining' => 'decimal:2',
        'budget_utilization_percentage' => 'decimal:2',
        'project_completion_rate' => 'decimal:2',
        'task_completion_rate' => 'decimal:2',
        'on_time_delivery_rate' => 'decimal:2',
        'timeline_data' => 'array',
        'status_distribution' => 'array',
        'priority_distribution' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who created the report.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the report.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include active reports.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by report type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('report_type', $type);
    }

    /**
     * Scope a query to filter by period type.
     */
    public function scopeOfPeriod($query, $period)
    {
        return $query->where('period_type', $period);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('report_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to get the latest report.
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('report_date', 'desc');
    }

    /**
     * Get the latest analytics report.
     */
    public static function getLatestAnalytics()
    {
        return static::active()
            ->ofType(self::TYPE_ANALYTICS)
            ->latest()
            ->first();
    }

    /**
     * Get analytics data for dashboard.
     */
    public static function getDashboardAnalytics()
    {
        $latestReport = static::getLatestAnalytics();
        
        if (!$latestReport) {
            return static::generateDefaultAnalytics();
        }

        return [
            'total_projects' => $latestReport->total_projects,
            'active_tasks' => $latestReport->active_tasks,
            'budget_spent' => $latestReport->budget_spent,
            'team_members' => $latestReport->total_team_members,
            'team_members_on_leave' => $latestReport->team_members_on_leave,
            'project_completion_rate' => $latestReport->project_completion_rate,
            'task_completion_rate' => $latestReport->task_completion_rate,
            'status_distribution' => $latestReport->status_distribution,
            'priority_distribution' => $latestReport->priority_distribution,
            'timeline_data' => $latestReport->timeline_data,
        ];
    }

    /**
     * Generate default analytics when no report exists.
     */
    public static function generateDefaultAnalytics()
    {
        // Get real-time data from related models
        $totalProjects = Project::count();
        $activeTasks = Task::where('status', Task::STATUS_IN_PROGRESS)->count();
        $totalBudget = Project::sum('budget');
        $budgetSpent = Project::sum('budget_spent');
        $teamMembers = TeamParing::count();
        $teamOnLeave = TeamParing::where('status', 'on_leave')->count();

        return [
            'total_projects' => $totalProjects,
            'active_tasks' => $activeTasks,
            'budget_spent' => $budgetSpent,
            'team_members' => $teamMembers,
            'team_members_on_leave' => $teamOnLeave,
            'project_completion_rate' => $totalProjects > 0 ? round((Project::where('status', 'completed')->count() / $totalProjects) * 100, 2) : 0,
            'task_completion_rate' => $activeTasks > 0 ? round((Task::where('status', Task::STATUS_COMPLETED)->count() / Task::count()) * 100, 2) : 0,
            'status_distribution' => static::getStatusDistribution(),
            'priority_distribution' => static::getPriorityDistribution(),
            'timeline_data' => static::getTimelineData(),
        ];
    }

    /**
     * Get project status distribution.
     */
    public static function getStatusDistribution()
    {
        return [
            'completed' => Project::where('status', 'completed')->count(),
            'in_progress' => Project::where('status', 'in_progress')->count(),
            'on_hold' => Project::where('status', 'on_hold')->count(),
            'not_started' => Project::where('status', 'not_started')->count(),
        ];
    }

    /**
     * Get task priority distribution.
     */
    public static function getPriorityDistribution()
    {
        return [
            'high' => Task::where('priority', Task::PRIORITY_HIGH)->count(),
            'medium' => Task::where('priority', Task::PRIORITY_MEDIUM)->count(),
            'low' => Task::where('priority', Task::PRIORITY_LOW)->count(),
        ];
    }

    /**
     * Get timeline data for charts.
     */
    public static function getTimelineData()
    {
        $months = [];
        $completedData = [];
        $inProgressData = [];
        $plannedData = [];
        $delayedData = [];

        // Get last 6 months data
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');
            
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $completedData[] = Task::where('status', Task::STATUS_COMPLETED)
                ->whereBetween('completed_date', [$startOfMonth, $endOfMonth])
                ->count();
                
            $inProgressData[] = Task::where('status', Task::STATUS_IN_PROGRESS)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
                
            $plannedData[] = Task::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
                
            $delayedData[] = Task::where('due_date', '<', now())
                ->where('status', '!=', Task::STATUS_COMPLETED)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
        }

        return [
            'months' => $months,
            'completed' => $completedData,
            'in_progress' => $inProgressData,
            'planned' => $plannedData,
            'delayed' => $delayedData,
        ];
    }

    /**
     * Generate and save a new analytics report.
     */
    public static function generateReport($periodType = self::PERIOD_MONTHLY, $userId = null)
    {
        $report = new static();
        $report->report_name = ucfirst($periodType) . ' Analytics Report - ' . now()->format('M Y');
        $report->report_type = self::TYPE_ANALYTICS;
        $report->report_date = now()->toDateString();
        $report->period_type = $periodType;
        $report->created_by = $userId;

        // Get real-time data
        $report->total_projects = Project::count();
        $report->active_projects = Project::where('status', 'in_progress')->count();
        $report->completed_projects = Project::where('status', 'completed')->count();
        $report->on_hold_projects = Project::where('status', 'on_hold')->count();
        $report->cancelled_projects = Project::where('status', 'cancelled')->count();

        $report->total_tasks = Task::count();
        $report->active_tasks = Task::where('status', Task::STATUS_IN_PROGRESS)->count();
        $report->completed_tasks = Task::where('status', Task::STATUS_COMPLETED)->count();
        $report->pending_tasks = Task::where('status', Task::STATUS_PENDING)->count();
        $report->overdue_tasks = Task::where('due_date', '<', now())
            ->where('status', '!=', Task::STATUS_COMPLETED)
            ->count();

        $report->high_priority_tasks = Task::where('priority', Task::PRIORITY_HIGH)->count();
        $report->medium_priority_tasks = Task::where('priority', Task::PRIORITY_MEDIUM)->count();
        $report->low_priority_tasks = Task::where('priority', Task::PRIORITY_LOW)->count();

        $report->total_budget = Project::sum('budget');
        $report->budget_spent = Project::sum('budget_spent');
        $report->budget_remaining = $report->total_budget - $report->budget_spent;
        $report->budget_utilization_percentage = $report->total_budget > 0 
            ? round(($report->budget_spent / $report->total_budget) * 100, 2) 
            : 0;

        $report->total_team_members = TeamParing::count();
        $report->active_team_members = TeamParing::where('status', 'active')->count();
        $report->team_members_on_leave = TeamParing::where('status', 'on_leave')->count();
        $report->team_members_available = $report->total_team_members - $report->team_members_on_leave;

        // Calculate performance metrics
        $report->project_completion_rate = $report->total_projects > 0 
            ? round(($report->completed_projects / $report->total_projects) * 100, 2) 
            : 0;
            
        $report->task_completion_rate = $report->total_tasks > 0 
            ? round(($report->completed_tasks / $report->total_tasks) * 100, 2) 
            : 0;

        // Store JSON data
        $report->timeline_data = static::getTimelineData();
        $report->status_distribution = static::getStatusDistribution();
        $report->priority_distribution = static::getPriorityDistribution();

        $report->save();

        return $report;
    }
}