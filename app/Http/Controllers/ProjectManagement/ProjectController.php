<?php

namespace App\Http\Controllers\ProjectManagement;

use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\Project;
use App\Models\CompanySubUser;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Auth, Session, Log, Validator, DB, Response};
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Carbon\Carbon;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects.
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Validate user authentication
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Please login to continue'
                ], 401);
            }
    
            // Get the current company ID from session
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                Log::warning('No company ID in session for projects');
                return response()->json([
                    'error' => true,
                    'message' => 'Company session expired. Please login again.'
                ], 403);
            }
    
            // Fetch non-deleted projects for the current company with pagination
            $projects = Project::where('company_id', $companyId)
                ->whereNull('deleted_at')
                ->with(['manager'])
                ->orderBy('created_at', 'desc')
                ->paginate(5);
    
            Log::info('Fetched active projects', [
                'company_id' => $companyId,
                'active_project_count' => $projects->total()
            ]);
    
            return response()->json([
                'error' => false,
                'message' => 'Projects fetched successfully',
                'data' => $projects->items(),
                'pagination' => [
                    'total' => $projects->total(),
                    'per_page' => $projects->perPage(), 
                    'current_page' => $projects->currentPage(),
                    'last_page' => $projects->lastPage(),
                    'from' => $projects->firstItem(),
                    'to' => $projects->lastItem(),
                    'next_page_url' => $projects->nextPageUrl(),
                    'prev_page_url' => $projects->previousPageUrl()
                ]
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error fetching projects', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'company_id' => $companyId ?? 'Unknown'
            ]);
    
            return response()->json([
                'error' => true,
                'message' => 'Error fetching projects: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Store a newly created project.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Project store method called', [
                'request_data' => $request->all(),
                'is_ajax' => $request->ajax()
            ]);

            // Validate user authentication
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                Log::error('Unauthorized access - user not authenticated');
                return $request->ajax() 
                    ? response()->json(['error' => 'Unauthorized access'], 401)
                    : redirect()->back()->with('error', 'Unauthorized access');
            }

            // Get the current company ID
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                Log::error('Company session expired - no company_id in session');
                return $request->ajax() 
                    ? response()->json(['error' => 'Company session expired'], 401)
                    : redirect()->back()->with('error', 'Company session expired');
            }

            Log::info('Company ID from session', ['company_id' => $companyId]);

            // Validate input - only fields that exist in the form
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'project_type' => 'required|string|max:255',
                'project_manager_id' => 'required|exists:company_sub_users,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'budget' => 'required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ]);
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            Log::info('Validation passed, proceeding to create project');

            // Verify manager belongs to company
            $manager = CompanySubUser::where('id', $request->project_manager_id)
                ->where('company_id', $companyId)
                ->first();
            
            if (!$manager) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Invalid project manager selected'], 422);
                }
                return redirect()->back()->with('error', 'Invalid project manager selected');
            }

            // Generate unique project code
            $projectCode = 'PRJ-' . strtoupper(substr($request->input('name'), 0, 3)) . '-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            
            // Ensure project code is unique
            while (Project::where('project_code', $projectCode)->exists()) {
                $projectCode = 'PRJ-' . strtoupper(substr($request->input('name'), 0, 3)) . '-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            }

            // Create new project - only with form fields
            $project = new Project();
            $project->company_id = $companyId;
            $project->name = $request->input('name');
            $project->project_code = $projectCode;
            $project->description = $request->input('description');
            $project->project_type = $request->input('project_type');
            $project->project_manager_id = $request->input('project_manager_id');
            $project->start_date = $request->input('start_date');
            $project->end_date = $request->input('end_date');
            $project->budget = $request->input('budget');
            $project->status = Project::STATUS_NOT_STARTED;
            $project->progress = 0;
            
            $saveResult = $project->save();

            if ($saveResult) {
                Log::info('Project created successfully', [
                    'project_id' => $project->id,
                    'company_id' => $companyId,
                    'project_name' => $project->name
                ]);

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Project added successfully',
                        'data' => $project->load(['manager'])
                    ]);
                }

                return redirect()->back()->with('success', 'Project added successfully');
            } else {
                Log::error('Failed to save project to database');
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to create project',
                        'errors' => ['Failed to save project to database']
                    ], 500);
                }

                return redirect()->back()->with('error', 'Failed to create project');
            }

        } catch (\Exception $e) {
            Log::error('Error creating project', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating project: ' . $e->getMessage(),
                    'errors' => [$e->getMessage()]
                ], 500);
            }

            return redirect()->back()->with('error', 'Error creating project: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified project.
     */
    public function show($id)
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return response()->json(['error' => 'Unauthorized access'], 401);
            }
    
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['error' => 'Company session expired'], 401);
            }

            Log::info('Fetching project for view', ['id' => $id, 'company_id' => $companyId]);

            $project = Project::where('id', $id)
                ->where('company_id', $companyId)
                ->with(['manager'])
                ->firstOrFail();
    
            Log::info('Project data fetched for view', $project->toArray());
    
            return response()->json([
                'success' => true,
                'message' => 'Project fetched successfully',
                'data' => $project
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching project for view: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch project: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing a project.
     */
    public function edit($id)
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return response()->json(['error' => 'Unauthorized access'], 401);
            }
    
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['error' => 'Company session expired'], 401);
            }

            Log::info('Attempting to fetch project with ID:', ['id' => $id, 'company_id' => $companyId]);

            $project = Project::where('id', $id)
                ->where('company_id', $companyId)
                ->with(['manager'])
                ->firstOrFail();
    
            Log::info('Project data fetched for edit:', $project->toArray());
    
            return response()->json($project);
        } catch (\Exception $e) {
            Log::error('Error preparing project edit: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to prepare edit: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing project's information
     */
    public function update(Request $request, $id)
    {
        try {
            // Get the current company ID from session
            $companyId = Session::get('selected_company_id');
            Log::info('Update request received', [
                'company_id' => $companyId,
                'project_id' => $id,
                'is_ajax' => $request->ajax(),
                'request_method' => $request->method()
            ]);

            if (!$companyId) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Company session expired'
                    ], 401);
                }
                return redirect()->back()->with('error', 'Company session expired');
            }

            // Fetch the project
            Log::info('Fetching project for update', ['id' => $id, 'company_id' => $companyId]);
            $project = Project::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            Log::info('Current project data before update', $project->toArray());

            // Check if this is a status update
            if (
                $request->ajax() &&
                $request->has('status') &&
                (count($request->all()) === 1 || (count($request->all()) === 2 && $request->has('_method')))
            ) {
                return $this->updateStatus($request, $id);
            }

            Log::info('Request data for update', [
                'all_data' => $request->all()
            ]);

            // Validate incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'project_type' => 'required|string|max:255',
                'project_manager_id' => 'nullable|exists:company_sub_users,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'budget' => 'required|numeric|min:0',
                'actual_cost' => 'nullable|numeric|min:0',
                'status' => 'nullable|string',
                'progress' => 'nullable|integer|min:0|max:100',
                'client_name' => 'nullable|string|max:255',
                'client_email' => 'nullable|email|max:255',
                'client_phone' => 'nullable|string|max:20',
                'notes' => 'nullable|string|max:1000',
                'objectives' => 'nullable|string',
                'deliverables' => 'nullable|string'
            ]);

            Log::info('Validated data for update', [
                'validated_data' => $validatedData
            ]);

            // Update the project's information
            Log::info('Attempting to update project', [
                'project_id' => $project->id,
                'data_to_update' => $validatedData
            ]);
            
            $project->update($validatedData);

            Log::info('Project updated successfully', [
                'project_id' => $project->id,
                'company_id' => $companyId,
                'updated_data' => $project->toArray()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Project has been updated successfully',
                    'data' => $project->load(['manager'])
                ]);
            }

            return redirect()->back()
                ->with('success', 'Project updated successfully');

        } catch (ValidationException $e) {
            Log::error('Validation error during project update', [
                'errors' => $e->errors(),
                'project_id' => $id,
                'company_id' => $companyId
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating project', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'project_id' => $id,
                'company_id' => $companyId
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update project. Please try again.',
                    'errors' => [$e->getMessage()]
                ], 500);
            }

            return back()->with('error', 'Failed to update project. Please try again.');
        }
    }

    /**
     * Soft delete a project
     */
    public function destroy($id)
    {
        try {
            // Validate user authentication
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return request()->ajax() 
                    ? response()->json(['error' => 'Unauthorized access'], 401)
                    : redirect()->back()->with('error', 'Unauthorized access');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return request()->ajax() 
                    ? response()->json(['error' => 'Company session expired'], 401)
                    : redirect()->back()->with('error', 'Company session expired');
            }

            $project = Project::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();
            
            Log::info('Deleting project', [
                'project_id' => $project->id,
                'project_name' => $project->name,
                'company_id' => $companyId
            ]);
            
            // Soft delete the project
            $project->delete();

            Log::info('Project deleted successfully', [
                'project_id' => $project->id,
                'company_id' => $companyId
            ]);

            // Return response based on request type
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Project has been moved to trash successfully'
                ]);
            }

            return redirect()->back()->with('success', 'Project has been moved to trash successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting project', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'project_id' => $id,
                'company_id' => $companyId ?? 'Unknown'
            ]);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to move project to trash. Please try again.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to move project to trash. Please try again.');
        }
    }

    /**
     * Handle AJAX status update for a project
     */
    public function updateStatus(Request $request, $id)
    {
        Log::info('updateStatus called', ['id' => $id, 'request' => $request->all()]);

        try {
            $companyId = Session::get('selected_company_id');

            $project = Project::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            $request->validate([
                'status' => 'required|string|in:' . implode(',', [
                    Project::STATUS_NOT_STARTED,
                    Project::STATUS_IN_PROGRESS,
                    Project::STATUS_ON_HOLD,
                    Project::STATUS_CANCELLED,
                    Project::STATUS_COMPLETED
                ])
            ]);

            Log::info('Status before update', [
                'project_id' => $project->id,
                'status' => $project->status,
                'company_id' => $companyId
            ]);

            $project->status = $request->input('status');
            $project->save();

            Log::info('Status after update', [
                'project_id' => $project->id,
                'status' => $project->status,
                'company_id' => $companyId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated',
                'data' => $project->load(['manager'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating project status', [
                'message' => $e->getMessage(),
                'project_id' => $id,
                'company_id' => isset($companyId) ? $companyId : null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    /**
     * Get company sub-users for project manager selection
     */
    public function getManagers()
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                Log::error('No company ID in session');
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired',
                    'data' => []
                ], 403);
            }

            Log::info('Starting to fetch managers for company ID: ' . $companyId);

            $managers = CompanySubUser::where('company_id', $companyId)
                ->whereIn('status', ['active', 1])
                ->select('id', 'fullname as name', 'email')
                ->orderBy('fullname')
                ->get();

            Log::info("Fetched {$managers->count()} managers");

            return response()->json([
                'success' => true,
                'message' => 'Managers retrieved successfully',
                'data' => $managers
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getManagers: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch managers: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }


    /**
     * Export projects to CSV.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function export(Request $request)
    {
        try {
            Log::info('Export method called', [
                'project_id' => $request->project_id,
                'all_params' => $request->all(),
                'headers' => $request->headers->all()
            ]);
            
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                throw new \Exception('Company session not found. Please log in again.');
            }
            
            // Get projects with relationships
            $query = Project::where('company_id', $companyId)
                ->with(['manager']);

            // Check if we're exporting a single project
            if ($request->has('project_id')) {
                $query->where('id', $request->project_id);
                $filename = 'project_' . $request->project_id . '_' . now()->format('Ymd_His') . '.csv';
            } else {
                $filename = 'projects_export_' . now()->format('Ymd_His') . '.csv';
            }

            $projects = $query->get();

            if ($projects->isEmpty()) {
                throw new \Exception('No projects found to export.');
            }

            // Prepare data for export
            $exportData = [];
            $exportData[] = [
                'ID',
                'Project Code',
                'Project Name',
                'Description',
                'Project Type',
                'Project Manager',
                'Start Date',
                'End Date',
                'Budget (' . config('app.currency', 'USD') . ')',
                'Actual Cost (' . config('app.currency', 'USD') . ')',
                'Progress (%)',
                'Status',
                'Created At',
                'Last Updated'
            ];

            foreach ($projects as $project) {
                $exportData[] = [
                    $project->id,
                    $project->code ?? 'N/A',
                    $project->name,
                    $project->description ?? 'N/A',
                    $project->project_type ?? 'N/A',
                    $project->manager ? $project->manager->fullname : 'Unassigned',
                    $project->start_date ? $project->start_date->format('Y-m-d') : 'N/A',
                    $project->end_date ? $project->end_date->format('Y-m-d') : 'N/A',
                    number_format($project->budget, 2),
                    number_format($project->actual_cost ?? 0, 2),
                    $project->progress,
                    ucwords(str_replace('_', ' ', $project->status)),
                    $project->created_at->format('Y-m-d H:i:s'),
                    $project->updated_at->format('Y-m-d H:i:s')
                ];
            }

            // Create a temporary file to store the CSV
            $tempFile = tempnam(sys_get_temp_dir(), 'export_');
            $handle = fopen($tempFile, 'w+');
            
            // Add BOM for proper UTF-8 encoding in Excel
            fwrite($handle, "\xEF\xBB\xBF");
            
            // Write CSV content
            foreach ($exportData as $row) {
                fputcsv($handle, $row);
            }
            
            // Get the file size
            fseek($handle, 0);
            $csv = stream_get_contents($handle);
            fclose($handle);
            
            // Return the file as a download response
            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
                'Content-Length' => strlen($csv),
            ];
            
            // Delete the temporary file after sending
            register_shutdown_function(function() use ($tempFile) {
                if (file_exists($tempFile)) {
                    unlink($tempFile);
                }
            });
            
            return response($csv, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Error exporting projects:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'company_id' => $companyId ?? 'unknown',
                'project_id' => $request->project_id ?? 'all'
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to export projects: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to export projects: ' . $e->getMessage());
        }
    }
}