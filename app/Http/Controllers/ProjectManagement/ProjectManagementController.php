<?php

namespace App\Http\Controllers\ProjectManagement;

use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\Project;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\PmReport;
use App\Models\TeamParing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Session, Log};
use Illuminate\View\View;

class ProjectManagementController extends Controller
{
    /**
     * Display the project management dashboard.
     */
    public function index(): View
    {
        try {
            // Validate user authentication
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                Log::error('Unauthorized access - user not authenticated');
                abort(403, 'Unauthorized access');
            }

            // Get the current company ID
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                Log::warning('No company_id in session, using default company ID 1 for testing');
                $companyId = 1; // Default company ID for testing
            }

            // Load projects with relationships
            $projects = Project::where('company_id', $companyId)
                ->with(['projectManager', 'team'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Load tasks with relationships
            $tasks = Task::where('company_id', $companyId)
                ->with(['project', 'assignedTeam'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Load teams
            $teams = TeamParing::where('company_id', $companyId)
                ->orderBy('team_name', 'asc')
                ->get();

            // Get analytics data for reports - SUPER SIMPLE HARDCODED FOR NOW
            $analytics = [
                'total_projects' => 9,  // Hardcoded for now
                'active_tasks' => 4,
                'budget_spent' => 50000,
                'team_members' => 2,
                'team_members_on_leave' => 0,
                'project_completion_rate' => 25,
                'task_completion_rate' => 30,
                'status_distribution' => [
                    'completed' => 2,
                    'in_progress' => 3,
                    'on_hold' => 1,
                    'not_started' => 3,
                ],
                'priority_distribution' => [
                    'high' => 2,
                    'medium' => 3,
                    'low' => 1,
                ],
                'timeline_data' => [
                    'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'completed' => [1, 2, 1, 2, 1, 2],
                    'in_progress' => [2, 1, 3, 2, 3, 1],
                    'planned' => [3, 2, 1, 2, 1, 2],
                    'delayed' => [0, 1, 0, 1, 0, 1],
                ],
                'last_updated' => 'Just now',
            ];

            Log::info('Project Management dashboard loaded', [
                'company_id' => $companyId,
                'projects_count' => $projects->count(),
                'tasks_count' => $tasks->count(),
                'teams_count' => $teams->count(),
                'analytics_total_projects' => $analytics['total_projects'] ?? 'NOT SET'
            ]);

            // Debug: Log the analytics data before passing to view
            Log::info('Passing analytics to view', [
                'analytics_keys' => array_keys($analytics),
                'total_projects' => $analytics['total_projects'] ?? 'NOT SET',
                'analytics_count' => count($analytics)
            ]);
            
            return view('company.ProjectManagement.pm', compact('projects', 'tasks', 'teams', 'analytics'));

        } catch (\Exception $e) {
            Log::error('Error loading project management dashboard', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            abort(500, 'Error loading project management dashboard');
        }
    }

    /**
     * Get default analytics data as fallback.
     * 
     * @return array
     */
    private function getDefaultAnalytics(): array
    {
        return [
            'total_projects' => 0,
            'active_tasks' => 0,
            'budget_spent' => 0,
            'team_members' => 0,
            'team_members_on_leave' => 0,
            'project_completion_rate' => 0,
            'task_completion_rate' => 0,
            'status_distribution' => [
                'completed' => 0,
                'in_progress' => 0,
                'on_hold' => 0,
                'not_started' => 0,
            ],
            'priority_distribution' => [
                'high' => 0,
                'medium' => 0,
                'low' => 0,
            ],
            'timeline_data' => [
                'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'completed' => [0, 0, 0, 0, 0, 0],
                'in_progress' => [0, 0, 0, 0, 0, 0],
                'planned' => [0, 0, 0, 0, 0, 0],
                'delayed' => [0, 0, 0, 0, 0, 0],
            ],
            'last_updated' => 'Never',
        ];
    }

    /**
     * Get analytics data for the reports section.
     * 
     * @param int $companyId
     * @return array
     */
    private function getAnalyticsData($companyId): array
    {
        try {
            Log::info('Getting analytics data for company ID: ' . $companyId);
            
            // Get project statistics
            $totalProjects = Project::where('company_id', $companyId)->count();
            Log::info('Total projects found: ' . $totalProjects);
            $activeProjects = Project::where('company_id', $companyId)
                ->where('status', 'in_progress')
                ->count();
            $completedProjects = Project::where('company_id', $companyId)
                ->where('status', 'completed')
                ->count();
            $onHoldProjects = Project::where('company_id', $companyId)
                ->where('status', 'on_hold')
                ->count();

            // Get task statistics
            $totalTasks = Task::whereHas('project', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->count();

            $activeTasks = Task::whereHas('project', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('status', Task::STATUS_IN_PROGRESS)->count();

            $completedTasks = Task::whereHas('project', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('status', Task::STATUS_COMPLETED)->count();

            $overdueTasks = Task::whereHas('project', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('due_date', '<', now())
            ->where('status', '!=', Task::STATUS_COMPLETED)
            ->count();

            // Get priority distribution
            $highPriorityTasks = Task::whereHas('project', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('priority', Task::PRIORITY_HIGH)->count();

            $mediumPriorityTasks = Task::whereHas('project', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('priority', Task::PRIORITY_MEDIUM)->count();

            $lowPriorityTasks = Task::whereHas('project', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('priority', Task::PRIORITY_LOW)->count();

            // Get budget information
            $totalBudget = Project::where('company_id', $companyId)->sum('budget');
            $budgetSpent = Project::where('company_id', $companyId)->sum('actual_cost');

            // Get team statistics
            $totalTeamMembers = TeamParing::where('company_id', $companyId)->count();
            $teamOnLeave = TeamParing::where('company_id', $companyId)
                ->where('team_status', 'on_leave')
                ->count();

            // Calculate completion rates
            $projectCompletionRate = $totalProjects > 0 
                ? round(($completedProjects / $totalProjects) * 100, 2) 
                : 0;

            $taskCompletionRate = $totalTasks > 0 
                ? round(($completedTasks / $totalTasks) * 100, 2) 
                : 0;

            return [
                'total_projects' => $totalProjects,
                'active_tasks' => $activeTasks,
                'budget_spent' => $budgetSpent,
                'team_members' => $totalTeamMembers,
                'team_members_on_leave' => $teamOnLeave,
                'project_completion_rate' => $projectCompletionRate,
                'task_completion_rate' => $taskCompletionRate,
                'status_distribution' => [
                    'completed' => $completedProjects,
                    'in_progress' => $activeProjects,
                    'on_hold' => $onHoldProjects,
                    'not_started' => $totalProjects - $completedProjects - $activeProjects - $onHoldProjects,
                ],
                'priority_distribution' => [
                    'high' => $highPriorityTasks,
                    'medium' => $mediumPriorityTasks,
                    'low' => $lowPriorityTasks,
                ],
                'timeline_data' => $this->getTimelineData($companyId),
                'last_updated' => 'Just now',
            ];
            
            return $analytics;
            
        } catch (\Exception $e) {
            Log::error('Error getting analytics data: ' . $e->getMessage());
            return [
                'total_projects' => 0,
                'active_tasks' => 0,
                'budget_spent' => 0,
                'team_members' => 0,
                'team_members_on_leave' => 0,
                'project_completion_rate' => 0,
                'task_completion_rate' => 0,
                'status_distribution' => [
                    'completed' => 0,
                    'in_progress' => 0,
                    'on_hold' => 0,
                    'not_started' => 0,
                ],
                'priority_distribution' => [
                    'high' => 0,
                    'medium' => 0,
                    'low' => 0,
                ],
                'timeline_data' => [
                    'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'completed' => [0, 0, 0, 0, 0, 0],
                    'in_progress' => [0, 0, 0, 0, 0, 0],
                    'planned' => [0, 0, 0, 0, 0, 0],
                    'delayed' => [0, 0, 0, 0, 0, 0],
                ],
                'last_updated' => 'Never',
            ];
        }
    }

    /**
     * Get timeline data for charts.
     * 
     * @param int $companyId
     * @return array
     */
    private function getTimelineData($companyId): array
    {
        try {
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
                
                $completedData[] = Task::whereHas('project', function($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })->where('status', Task::STATUS_COMPLETED)
                ->whereBetween('completed_date', [$startOfMonth, $endOfMonth])
                ->count();
                
                $inProgressData[] = Task::whereHas('project', function($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })->where('status', Task::STATUS_IN_PROGRESS)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
                
                $plannedData[] = Task::whereHas('project', function($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
                
                $delayedData[] = Task::whereHas('project', function($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })->where('due_date', '<', now())
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
            
        } catch (\Exception $e) {
            Log::error('Error getting timeline data: ' . $e->getMessage());
            return [
                'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'completed' => [0, 0, 0, 0, 0, 0],
                'in_progress' => [0, 0, 0, 0, 0, 0],
                'planned' => [0, 0, 0, 0, 0, 0],
                'delayed' => [0, 0, 0, 0, 0, 0],
            ];
        }
    }
}
