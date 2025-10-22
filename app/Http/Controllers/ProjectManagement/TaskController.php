<?php

namespace App\Http\Controllers\ProjectManagement;

use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\Project;
use App\Models\TeamParing;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Auth, Session, Log, Validator, DB};
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index()
    {
        // This method is not used since tasks.blade.php loads data directly
        // The tasks component is included in the main project management view
        return redirect()->back();
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Task store method called', [
                'request_data' => $request->all()
            ]);

            // Validate user authentication
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                Log::error('Unauthorized access - user not authenticated');
                return redirect()->back()->with('error', 'Unauthorized access');
            }

            // Get the current company ID
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                Log::error('Company session expired - no company_id in session');
                return redirect()->back()->with('error', 'Company session expired');
            }

            Log::info('Company ID from session', ['company_id' => $companyId]);

            // Validate input
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'project_id' => 'required|exists:projects,id',
                'assigned_team_id' => 'required|exists:team_paring,id',
                'due_date' => 'required|date|after_or_equal:today',
                'priority' => 'required|in:low,medium,high',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ]);
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            Log::info('Validation passed, proceeding to create task');

            // Verify project belongs to company
            $project = Project::where('id', $request->project_id)
                ->where('company_id', $companyId)
                ->first();

            if (!$project) {
                Log::error('Project not found or does not belong to company', [
                    'project_id' => $request->project_id,
                    'company_id' => $companyId
                ]);
                
                return redirect()->back()->with('error', 'Project not found or does not belong to your company');
            }

            // Verify team belongs to company
            $team = TeamParing::where('id', $request->assigned_team_id)
                ->where('company_id', $companyId)
                ->first();

            if (!$team) {
                Log::error('Team not found or does not belong to company', [
                    'team_id' => $request->assigned_team_id,
                    'company_id' => $companyId
                ]);
                
                return redirect()->back()->with('error', 'Team not found or does not belong to your company');
            }

            // Create the task
            $task = Task::create([
                'company_id' => $companyId,
                'title' => $request->title,
                'description' => $request->description,
                'project_id' => $request->project_id,
                'assigned_team_id' => $request->assigned_team_id,
                'due_date' => $request->due_date,
                'priority' => $request->priority,
                'status' => 'pending',
                'progress' => 0,
                'notes' => $request->notes,
            ]);

            Log::info('Task created successfully', [
                'task_id' => $task->id,
                'task_code' => $task->task_code,
                'company_id' => $companyId
            ]);

            return redirect()->back()->with('success', 'Task created successfully')->with('active_tab', 'tasks');

        } catch (\Exception $e) {
            Log::error('Error creating task', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'company_id' => $companyId ?? 'Unknown',
                'request_data' => $request->all()
            ]);

            return redirect()->back()->with('error', 'Failed to create task. Please try again.');
        }
    }

    /**
     * Display the specified task.
     */
    public function show(string $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $task = Task::where('id', $id)
                ->where('company_id', $companyId)
                ->with(['project', 'assignedTeam'])
                ->first();

            if (!$task) {
                return response()->json([
                    'error' => true,
                    'message' => 'Task not found'
                ], 404);
            }

            return response()->json([
                'error' => false,
                'data' => $task
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching task', [
                'error' => $e->getMessage(),
                'task_id' => $id
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Error fetching task'
            ], 500);
        }
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, string $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $task = Task::where('id', $id)
                ->where('company_id', $companyId)
                ->first();

            if (!$task) {
                return redirect()->back()->with('error', 'Task not found');
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'project_id' => 'required|exists:projects,id',
                'assigned_team_id' => 'required|exists:team_paring,id',
                'due_date' => 'required|date',
                'priority' => 'required|in:low,medium,high',
                'status' => 'nullable|in:pending,in_progress,completed,cancelled,on_hold',
                'progress' => 'nullable|integer|min:0|max:100',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Update the task
            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'project_id' => $request->project_id,
                'assigned_team_id' => $request->assigned_team_id,
                'due_date' => $request->due_date,
                'priority' => $request->priority,
                'status' => $request->status ?? $task->status,
                'progress' => $request->progress ?? $task->progress,
                'notes' => $request->notes,
            ]);

            // Set completed date if status is completed
            if ($request->status === 'completed' && $task->status !== 'completed') {
                $task->update(['completed_date' => now()]);
            }

            Log::info('Task updated successfully', [
                'task_id' => $task->id,
                'company_id' => $companyId
            ]);

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Task updated successfully'
                ]);
            }
            
            return redirect()->back()
                ->with('success', 'Task updated successfully')
                ->with('active_tab', 'tasks');

        } catch (\Exception $e) {
            Log::error('Error updating task', [
                'error' => $e->getMessage(),
                'task_id' => $id
            ]);

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update task. Please try again.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update task. Please try again.');
        }
    }

    /**
     * Update task status.
     */
    public function updateStatus(Request $request, string $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $task = Task::where('id', $id)
                ->where('company_id', $companyId)
                ->first();

            if (!$task) {
                return redirect()->back()->with('error', 'Task not found');
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,in_progress,completed,cancelled,on_hold'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Invalid status');
            }

            $task->update(['status' => $request->status]);

            // Set completed date if status is completed
            if ($request->status === 'completed' && $task->completed_date === null) {
                $task->update(['completed_date' => now()]);
            }

            return redirect()->back()->with('success', 'Task status updated successfully')->with('active_tab', 'tasks');

        } catch (\Exception $e) {
            Log::error('Error updating task status', [
                'error' => $e->getMessage(),
                'task_id' => $id
            ]);

            return redirect()->back()->with('error', 'Failed to update task status');
        }
    }

    /**
     * Remove the specified task.
     */
    public function destroy(string $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $task = Task::where('id', $id)
                ->where('company_id', $companyId)
                ->first();

            if (!$task) {
                return redirect()->back()->with('error', 'Task not found');
            }

            $task->delete();

            Log::info('Task deleted successfully', [
                'task_id' => $id,
                'company_id' => $companyId
            ]);

            return redirect()->back()->with('success', 'Task deleted successfully')->with('active_tab', 'tasks');

        } catch (\Exception $e) {
            Log::error('Error deleting task', [
                'error' => $e->getMessage(),
                'task_id' => $id
            ]);

            return redirect()->back()->with('error', 'Failed to delete task');
        }
    }

    /**
     * Get projects for dropdown.
     */
    public function getProjects()
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $projects = Project::where('company_id', $companyId)
                ->whereNull('deleted_at')
                ->select('id', 'name', 'project_code')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $projects
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching projects for tasks', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch projects'
            ], 500);
        }
    }

    /**
     * Get teams for dropdown.
     */
    public function getTeams()
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $teams = TeamParing::where('company_id', $companyId)
                ->whereNull('deleted_at')
                ->select('id', 'team_name')
                ->orderBy('team_name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $teams
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching teams for tasks', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch teams'
            ], 500);
        }
    }
}
