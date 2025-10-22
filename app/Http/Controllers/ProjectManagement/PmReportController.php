<?php

namespace App\Http\Controllers\ProjectManagement;

use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\PmReport;
use App\Models\ProjectManagement\Project;
use App\Models\ProjectManagement\Task;
use App\Models\TeamParing;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Auth, Session, Log, DB, Response};
use Illuminate\View\View;
use Carbon\Carbon;

class PmReportController extends Controller
{
    /**
     * Display the analytics dashboard.
     * 
     * @return View
     */
    public function index(): View
    {
        try {
            // Validate user authentication
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return redirect()->route('login')->with('error', 'Please login to continue');
            }

            // Get the current company ID from session
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                Log::warning('No company ID in session for reports');
                return redirect()->back()->with('error', 'Company session expired. Please login again.');
            }

            // Get analytics data
            $analytics = $this->getAnalyticsData($companyId);
            
            return view('company.ProjectManagement.components.reports', compact('analytics'));
            
        } catch (\Exception $e) {
            Log::error('Error loading reports dashboard: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load reports. Please try again.');
        }
    }

    /**
     * Get analytics data for the dashboard.
     * 
     * @param int $companyId
     * @return array
     */
    public function getAnalyticsData($companyId): array
    {
        try {
            // Get the latest report or generate default data
            $latestReport = PmReport::where('company_id', $companyId)
                ->active()
                ->latest()
                ->first();

            if ($latestReport) {
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
                    'last_updated' => $latestReport->updated_at->diffForHumans(),
                ];
            }

            // Generate real-time data if no report exists
            return $this->generateRealTimeAnalytics($companyId);
            
        } catch (\Exception $e) {
            Log::error('Error getting analytics data: ' . $e->getMessage());
            return $this->getDefaultAnalytics();
        }
    }

    /**
     * Generate real-time analytics data.
     * 
     * @param int $companyId
     * @return array
     */
    private function generateRealTimeAnalytics($companyId): array
    {
        try {
            // Get project statistics
            $totalProjects = Project::where('company_id', $companyId)->count();
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
            $budgetSpent = Project::where('company_id', $companyId)->sum('budget_spent');

            // Get team statistics
            $totalTeamMembers = TeamParing::where('company_id', $companyId)->count();
            $teamOnLeave = TeamParing::where('company_id', $companyId)
                ->where('status', 'on_leave')
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
            
        } catch (\Exception $e) {
            Log::error('Error generating real-time analytics: ' . $e->getMessage());
            return $this->getDefaultAnalytics();
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

    /**
     * Get default analytics when data is unavailable.
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
     * Get analytics data via AJAX.
     * 
     * @return JsonResponse
     */
    public function getAnalytics(): JsonResponse
    {
        try {
            // Validate user authentication
            if (!Auth::check()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Please login to continue'
                ], 401);
            }

            // Get the current company ID from session
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'error' => true,
                    'message' => 'Company session expired. Please login again.'
                ], 403);
            }

            $analytics = $this->getAnalyticsData($companyId);

            return response()->json([
                'error' => false,
                'data' => $analytics
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting analytics via AJAX: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Failed to load analytics data'
            ], 500);
        }
    }

    /**
     * Get priority tasks for a specific priority level.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getPriorityTasks(Request $request): JsonResponse
    {
        try {
            // Validate user authentication
            if (!Auth::check()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Please login to continue'
                ], 401);
            }

            // Get the current company ID from session
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'error' => true,
                    'message' => 'Company session expired. Please login again.'
                ], 403);
            }

            $priority = $request->input('priority', 'high');
            $limit = $request->input('limit', 10);

            // Validate priority
            if (!in_array($priority, [Task::PRIORITY_HIGH, Task::PRIORITY_MEDIUM, Task::PRIORITY_LOW])) {
                return response()->json([
                    'error' => true,
                    'message' => 'Invalid priority level'
                ], 400);
            }

            $tasks = Task::whereHas('project', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->where('priority', $priority)
            ->with(['project', 'assignedTeam'])
            ->orderBy('due_date', 'asc')
            ->limit($limit)
            ->get();

            $formattedTasks = $tasks->map(function($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'project' => $task->project->name ?? 'N/A',
                    'due_date' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
                    'status' => $task->status,
                    'assignee' => $task->assignedTeam->name ?? 'Unassigned',
                    'priority' => $task->priority,
                ];
            });

            return response()->json([
                'error' => false,
                'data' => $formattedTasks,
                'total' => $tasks->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting priority tasks: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Failed to load priority tasks'
            ], 500);
        }
    }

    /**
     * Export priority tasks data
     */
    public function exportData(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            $exportType = $request->input('type', 'tasks');
            $format = $request->input('format', 'csv');
            $priority = $request->input('priority', 'all');
            $dateRange = $request->input('dateRange', 'all');
            $includeCompleted = $request->input('includeCompleted', true);
            $includeDetails = $request->input('includeDetails', true);

            // Debug logging
            Log::info('Export request received', [
                'companyId' => $companyId,
                'exportType' => $exportType,
                'format' => $format,
                'priority' => $priority,
                'allInputs' => $request->all()
            ]);

            // Handle different export types
            if ($exportType === 'reports') {
                // Export all project analytics data
                return $this->exportProjectAnalytics($format, $companyId);
            }

            // Get tasks based on priority
            $query = Task::whereHas('project', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })->with(['project', 'assignedTeam']);

            // Filter by priority if specified
            if ($priority !== 'all') {
                $query->where('priority', $priority);
            }

            // Filter by date range
            if ($dateRange !== 'all') {
                $startDate = $this->getDateRangeStart($dateRange);
                if ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                }
            }

            // Filter completed tasks if needed
            if (!$includeCompleted) {
                $query->where('status', '!=', 'completed');
            }

            $tasks = $query->get();

            // Format data for export
            $exportData = $tasks->map(function($task) use ($includeDetails) {
                $data = [
                    'Task ID' => $task->id,
                    'Task Title' => $task->title,
                    'Project' => $task->project->name ?? 'No Project',
                    'Priority' => ucfirst($task->priority),
                    'Status' => ucfirst(str_replace('_', ' ', $task->status)),
                    'Assigned Team' => $task->assignedTeam->team_name ?? 'Unassigned',
                    'Due Date' => $task->due_date ? $task->due_date->format('Y-m-d') : 'No due date',
                    'Created Date' => $task->created_at->format('Y-m-d H:i:s'),
                ];

                if ($includeDetails) {
                    $data['Description'] = $task->description ?? '';
                    $data['Progress'] = $task->progress . '%';
                    $data['Task Code'] = $task->task_code ?? '';
                }

                return $data;
            });

            // Generate filename
            $filename = 'priority_tasks_' . $priority . '_' . now()->format('Y-m-d_H-i-s');

            // Return appropriate format
            switch ($format) {
                case 'csv':
                    return $this->exportToCsv($exportData, $filename . '.csv');
                case 'excel':
                    return $this->exportToExcel($exportData, $filename . '.xlsx');
                case 'pdf':
                    return $this->exportToPdf($exportData, $filename . '.pdf', $priority);
                default:
                    return response()->json(['error' => 'Invalid format'], 400);
            }

        } catch (\Exception $e) {
            Log::error('Error exporting data: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Failed to export data'
            ], 500);
        }
    }

    /**
     * Get date range start date
     */
    private function getDateRangeStart($dateRange)
    {
        switch ($dateRange) {
            case 'today':
                return now()->startOfDay();
            case 'week':
                return now()->subWeek()->startOfDay();
            case 'month':
                return now()->subMonth()->startOfDay();
            case 'quarter':
                return now()->subQuarter()->startOfDay();
            case 'year':
                return now()->subYear()->startOfDay();
            default:
                return null;
        }
    }

    /**
     * Export to CSV
     */
    private function exportToCsv($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Write headers
            if (!empty($data)) {
                fputcsv($file, array_keys($data->first()));
            }
            
            // Write data
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to Excel (CSV format that Excel can open)
     */
    private function exportToExcel($data, $filename)
    {
        // Generate CSV with proper Excel-compatible formatting
        $csvFilename = str_replace('.xlsx', '.csv', $filename);
        return $this->exportToCsv($data, $csvFilename);
    }

    /**
     * Export to PDF (HTML format that can be printed to PDF)
     */
    private function exportToPdf($data, $filename, $priority)
    {
        // Generate HTML that can be opened in browser and printed to PDF
        $htmlFilename = str_replace('.pdf', '.html', $filename);
        
        $html = $this->generateHtmlReport($data, $priority);
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $htmlFilename . '"');
    }

    /**
     * Export Project Analytics & Reports
     */
    private function exportProjectAnalytics($format, $companyId)
    {
        try {
            // Get all analytics data
            $totalProjects = Project::where('company_id', $companyId)->count();
            $activeTasks = Task::whereHas('project', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->whereIn('status', ['pending', 'in_progress'])->count();
            $budgetSpent = Project::where('company_id', $companyId)->sum('budget');
            $teamMembers = TeamParing::where('company_id', $companyId)->count();

            // Get project status distribution
            $completedProjects = Project::where('company_id', $companyId)->where('status', 'completed')->count();
            $inProgressProjects = Project::where('company_id', $companyId)->where('status', 'in_progress')->count();
            $onHoldProjects = Project::where('company_id', $companyId)->where('status', 'on_hold')->count();
            $notStartedProjects = Project::where('company_id', $companyId)->where('status', 'not_started')->count();

            // Get task priority distribution
            $highPriorityTasks = Task::whereHas('project', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('priority', 'high')->count();
            $mediumPriorityTasks = Task::whereHas('project', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('priority', 'medium')->count();
            $lowPriorityTasks = Task::whereHas('project', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('priority', 'low')->count();

            // Get task status distribution
            $completedTasks = Task::whereHas('project', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('status', 'completed')->count();
            $pendingTasks = Task::whereHas('project', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('status', 'pending')->count();
            $inProgressTasks = Task::whereHas('project', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('status', 'in_progress')->count();

            // Prepare export data
            $exportData = collect([
                ['Metric', 'Value'],
                ['', ''],
                ['PROJECT OVERVIEW', ''],
                ['Total Projects', $totalProjects],
                ['Active Tasks', $activeTasks],
                ['Total Budget', '₵' . number_format($budgetSpent, 2)],
                ['Team Members', $teamMembers],
                ['', ''],
                ['PROJECT STATUS DISTRIBUTION', ''],
                ['Completed Projects', $completedProjects],
                ['In Progress Projects', $inProgressProjects],
                ['On Hold Projects', $onHoldProjects],
                ['Not Started Projects', $notStartedProjects],
                ['', ''],
                ['TASK PRIORITY DISTRIBUTION', ''],
                ['High Priority Tasks', $highPriorityTasks],
                ['Medium Priority Tasks', $mediumPriorityTasks],
                ['Low Priority Tasks', $lowPriorityTasks],
                ['', ''],
                ['TASK STATUS DISTRIBUTION', ''],
                ['Completed Tasks', $completedTasks],
                ['Pending Tasks', $pendingTasks],
                ['In Progress Tasks', $inProgressTasks],
                ['', ''],
                ['REPORT INFORMATION', ''],
                ['Generated Date', now()->format('Y-m-d H:i:s')],
                ['Company ID', $companyId],
            ]);

            // Generate filename
            $filename = 'project_analytics_reports_' . now()->format('Y-m-d_H-i-s');

            // Return appropriate format
            switch ($format) {
                case 'csv':
                    return $this->exportToCsv($exportData, $filename . '.csv');
                case 'excel':
                    return $this->exportToExcel($exportData, $filename . '.xlsx');
                case 'pdf':
                    return $this->exportAnalyticsToPdf($exportData, $filename . '.pdf');
                default:
                    return response()->json(['error' => 'Invalid format'], 400);
            }

        } catch (\Exception $e) {
            Log::error('Error exporting project analytics: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Failed to export project analytics'
            ], 500);
        }
    }

    /**
     * Export Analytics to PDF (HTML format that can be printed to PDF)
     */
    private function exportAnalyticsToPdf($data, $filename)
    {
        // Generate HTML that can be opened in browser and printed to PDF
        $htmlFilename = str_replace('.pdf', '.html', $filename);
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Project Analytics & Reports</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; text-align: center; }
        h2 { color: #666; margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .section-header { background-color: #e9ecef; font-weight: bold; }
        .header { margin-bottom: 30px; }
        .footer { margin-top: 30px; font-size: 12px; color: #666; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Project Analytics & Reports</h1>
        <p><strong>Generated Date:</strong> ' . now()->format('Y-m-d H:i:s') . '</p>
        <p class="no-print"><strong>Note:</strong> You can print this page to PDF using your browser\'s print function (Ctrl+P)</p>
    </div>';

        $html .= '<table>
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($data as $row) {
            if (empty($row[0]) && empty($row[1])) {
                $html .= '<tr><td colspan="2">&nbsp;</td></tr>';
            } elseif (strpos($row[0], 'DISTRIBUTION') !== false || strpos($row[0], 'OVERVIEW') !== false || strpos($row[0], 'INFORMATION') !== false) {
                $html .= '<tr><td colspan="2" class="section-header">' . htmlspecialchars($row[0]) . '</td></tr>';
            } else {
                $html .= '<tr><td>' . htmlspecialchars($row[0]) . '</td><td>' . htmlspecialchars($row[1]) . '</td></tr>';
            }
        }

        $html .= '</tbody>
        </table>
    <div class="footer">
        <p>Generated by ERP System - ' . now()->format('Y-m-d H:i:s') . '</p>
    </div>
</body>
</html>';

        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $htmlFilename . '"');
    }

    /**
     * Generate HTML report for priority tasks
     */
    private function generateHtmlReport($data, $priority)
    {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . ucfirst($priority) . ' Priority Tasks Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>' . ucfirst($priority) . ' Priority Tasks Report</h1>
        <p>Generated on: ' . now()->format('Y-m-d H:i:s') . '</p>
        <p class="no-print"><strong>Note:</strong> You can print this page to PDF using your browser\'s print function (Ctrl+P)</p>
    </div>
    
    <table>
        <thead>
            <tr>';
        
        // Add headers from first row
        if (!empty($data)) {
            foreach (array_keys($data->first()) as $header) {
                $html .= '<th>' . htmlspecialchars($header) . '</th>';
            }
        }
        
        $html .= '
            </tr>
        </thead>
        <tbody>';
        
        // Add data rows
        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . htmlspecialchars($cell) . '</td>';
            }
            $html .= '</tr>';
        }
        
        $html .= '
        </tbody>
    </table>
    
    <div class="footer">
        <p>This report was generated automatically by the Project Management System</p>
    </div>
</body>
</html>';

        return $html;
    }
}
