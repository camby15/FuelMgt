<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationFolder;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DocumentationController extends Controller
{
    /**
     * Get comprehensive documentation statistics for dashboard
     */
    public function getStats(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            // Initialize stats with zeros
            $stats = [
                'total_documents' => 0,
                'pending_approvals' => 0,
                'recent_uploads' => 0,
                'storage_used' => 0,
                'active_users' => 0,
                'overdue_documents' => 0,
                'this_month_change' => 0,
                'latest_updates' => [],
                'by_category' => [],
                'by_type' => []
            ];

            try {
                $currentMonth = now()->startOfMonth();
                $lastMonth = now()->subMonth()->startOfMonth();
                
                // Get document counts safely
                $stats['total_documents'] = Documentation::where('company_id', $companyId)->count();
                
                // Pending approvals
                $stats['pending_approvals'] = Documentation::where('company_id', $companyId)
                    ->where('status', 'pending')->count();
                
                // Recent uploads (this month)
                $stats['recent_uploads'] = Documentation::where('company_id', $companyId)
                    ->where('created_at', '>=', $currentMonth)->count();

                // Calculate this month's change percentage
                $lastMonthCount = Documentation::where('company_id', $companyId)
                    ->where('created_at', '>=', $lastMonth)
                    ->where('created_at', '<', $currentMonth)
                    ->count();
                
                if ($lastMonthCount > 0) {
                    $stats['this_month_change'] = round((($stats['recent_uploads'] - $lastMonthCount) / $lastMonthCount) * 100, 1);
                } else {
                    $stats['this_month_change'] = $stats['recent_uploads'] > 0 ? 100 : 0;
                }

                // Storage used (convert to MB)
                $stats['storage_used'] = Documentation::where('company_id', $companyId)
                    ->sum('file_size') / (1024 * 1024);

                // Active users (users who have uploaded documents this month)
                $stats['active_users'] = Documentation::where('company_id', $companyId)
                    ->where('created_at', '>=', $currentMonth)
                    ->distinct('uploaded_by')
                    ->count('uploaded_by');

                // Overdue documents (documents pending for more than 7 days)
                $sevenDaysAgo = now()->subDays(7);
                $stats['overdue_documents'] = Documentation::where('company_id', $companyId)
                    ->where('status', 'pending')
                    ->where('created_at', '<=', $sevenDaysAgo)
                    ->count();

                // Latest updates (recent 5 documents)
                $stats['latest_updates'] = Documentation::with(['uploader', 'folder'])
                    ->where('company_id', $companyId)
                    ->orderBy('updated_at', 'desc')
                    ->limit(5)
                    ->get()
                    ->map(function($doc) {
                        return [
                            'id' => $doc->id,
                            'title' => $doc->title,
                            'category' => $doc->category,
                            'status' => $doc->status,
                            'updated_at' => $doc->updated_at,
                            'uploader_name' => $doc->uploader ? 
                                ($doc->uploader->first_name . ' ' . $doc->uploader->last_name) : 'Unknown',
                            'folder_name' => $doc->folder ? $doc->folder->name : 'Root'
                        ];
                    });

                // Documents by category
                $stats['by_category'] = Documentation::where('company_id', $companyId)
                    ->select('category', DB::raw('count(*) as count'))
                    ->groupBy('category')
                    ->get()
                    ->pluck('count', 'category');

                // Documents by type
                $stats['by_type'] = Documentation::where('company_id', $companyId)
                    ->select('file_type', DB::raw('count(*) as count'))
                    ->groupBy('file_type')
                    ->get()
                    ->pluck('count', 'file_type');

            } catch (\Exception $e) {
                Log::error('Error getting documentation stats: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Documentation statistics retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch documentation stats: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch documentation statistics.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a specific document
     */
    public function show(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $document = Documentation::with(['uploader', 'folder'])
                ->where('company_id', $companyId)
                ->where('id', $id)
                ->first();

            if (!$document) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $document,
                'message' => 'Document retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch document: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch document.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get documents list
     */
    public function index(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $query = Documentation::with(['uploader', 'folder'])
                ->where('company_id', $companyId);

            // Apply filters
            if ($request->has('category') && $request->category !== 'all') {
                $query->where('category', $request->category);
            }

            if ($request->has('type') && $request->type !== 'all') {
                $query->where('file_type', $request->type);
            }

            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            if ($request->has('search') && $request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%')
                      ->orWhere('tags', 'like', '%' . $request->search . '%');
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $documents = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $documents->items(),
                'pagination' => [
                    'current_page' => $documents->currentPage(),
                    'last_page' => $documents->lastPage(),
                    'per_page' => $documents->perPage(),
                    'total' => $documents->total()
                ],
                'message' => 'Documents retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch documents: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch documents.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a document
     */
    public function update(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $document = Documentation::where('company_id', $companyId)
                ->where('id', $id)
                ->first();

            if (!$document) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found.'
                ], 404);
            }

            $validator = \Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string|in:policy,procedure,form,template,contract,other',
                'access_level' => 'required|string|in:public,department,role,private',
                'status' => 'required|string|in:active,pending,archived,draft',
                'folder_id' => 'nullable|exists:documentation_folders,id',
                'tags' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update document
            $document->update([
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'access_level' => $request->access_level,
                'status' => $request->status,
                'folder_id' => $request->folder_id ?: null,
                'tags' => $request->tags
            ]);

            return response()->json([
                'success' => true,
                'data' => $document,
                'message' => 'Document updated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to update document: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update document.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new document
     */
    public function store(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $validator = \Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string|in:policy,procedure,form,template,contract,other',
                'file' => 'required|file|max:10240', // 10MB max
                'tags' => 'nullable|string',
                'access_level' => 'required|string|in:public,department,role,private',
                'folder_id' => 'nullable|exists:documentation_folders,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle file upload
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');
            $fileSize = $file->getSize();
            $fileType = $file->getClientOriginalExtension();

            $document = Documentation::create([
                'company_id' => $companyId,
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_type' => $fileType,
                'file_size' => $fileSize,
                'tags' => $request->tags,
                'access_level' => $request->access_level,
                'folder_id' => $request->folder_id,
                'status' => 'pending',
                'uploaded_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'data' => $document,
                'message' => 'Document uploaded successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to upload document: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Delete a document
     */
    public function destroy($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $document = Documentation::where('company_id', $companyId)->findOrFail($id);
            
            // Delete file from storage
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            
            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to delete document: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all folders for the company
     */
    public function getFolders(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id', 1); // Default to 1 if not set
            
            Log::info('getFolders called', [
                'company_id' => $companyId,
                'request_data' => $request->all()
            ]);

            // Simple query without relationships first
            $folders = DocumentationFolder::where('company_id', $companyId)
                ->orderBy('name')
                ->get();

            Log::info('Folders retrieved', [
                'count' => $folders->count(),
                'folders' => $folders->toArray()
            ]);

            return response()->json([
                'success' => true,
                'data' => $folders,
                'message' => 'Folders retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch folders: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch folders.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new folder
     */
    public function createFolder(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id', 1); // Default to 1 if not set
            
            Log::info('createFolder called', [
                'company_id' => $companyId,
                'request_data' => $request->all()
            ]);
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $validator = \Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'parent_id' => 'nullable|string', // Changed to string to handle "documents", "shared", etc.
                'access_level' => 'required|string|in:' . implode(',', [
                    DocumentationFolder::ACCESS_PUBLIC,
                    DocumentationFolder::ACCESS_DEPARTMENT,
                    DocumentationFolder::ACCESS_ROLE,
                    DocumentationFolder::ACCESS_PRIVATE
                ])
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle parent_id - convert string values to null for root folders
            $parentId = null;
            if ($request->parent_id && is_numeric($request->parent_id)) {
                $parentId = $request->parent_id;
            }

            // Check if folder name already exists in the same parent
            $existingFolder = DocumentationFolder::where('company_id', $companyId)
                ->where('name', $request->name)
                ->where('parent_id', $parentId)
                ->first();

            if ($existingFolder) {
                return response()->json([
                    'success' => false,
                    'message' => 'A folder with this name already exists in the selected location.'
                ], 422);
            }

            // Determine created_by - can be either user_id or employee_id
            // First try to find if current user is an employee
            $employee = \App\Models\Employee::where('user_id', Auth::id())->first();
            
            if ($employee) {
                // User is an employee - use employee ID
                $createdBy = $employee->id;
                $createdByType = DocumentationFolder::CREATED_BY_EMPLOYEE;
            } else {
                // User is not an employee (likely HR manager) - use user ID
                $createdBy = Auth::id();
                $createdByType = DocumentationFolder::CREATED_BY_USER;
            }

            Log::info('Folder creation - created_by determination', [
                'user_id' => Auth::id(),
                'employee_id' => $employee ? $employee->id : null,
                'created_by' => $createdBy,
                'created_by_type' => $createdByType
            ]);

            $folder = DocumentationFolder::create([
                'company_id' => $companyId,
                'name' => $request->name,
                'description' => $request->description,
                'parent_id' => $parentId,
                'access_level' => $request->access_level,
                'created_by' => $createdBy,
                'created_by_type' => $createdByType // Store the type for reference
            ]);

            $folder->load(['creator', 'parent']);

            return response()->json([
                'success' => true,
                'data' => $folder,
                'message' => 'Folder created successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to create folder: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create folder.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a folder
     */
    public function updateFolder(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id', 1); // Default to 1 if not set
            
            Log::info('updateFolder called', [
                'folder_id' => $id,
                'company_id' => $companyId,
                'request_data' => $request->all()
            ]);
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $folder = DocumentationFolder::where('company_id', $companyId)->findOrFail($id);

            $validator = \Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'parent_id' => 'nullable|string', // Changed to string to handle "documents", "shared", etc.
                'access_level' => 'sometimes|required|string|in:' . implode(',', [
                    DocumentationFolder::ACCESS_PUBLIC,
                    DocumentationFolder::ACCESS_DEPARTMENT,
                    DocumentationFolder::ACCESS_ROLE,
                    DocumentationFolder::ACCESS_PRIVATE
                ])
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle parent_id - convert string values to null for root folders
            $parentId = null;
            if ($request->parent_id && is_numeric($request->parent_id)) {
                $parentId = $request->parent_id;
            }

            // Prevent moving folder to itself or its children
            if ($parentId) {
                if ($parentId == $folder->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'A folder cannot be moved to itself.'
                    ], 422);
                }

                // Check if trying to move to a child folder
                $childIds = $folder->children()->pluck('id')->toArray();
                if (in_array($parentId, $childIds)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'A folder cannot be moved to its own subfolder.'
                    ], 422);
                }
            }

            // Check if folder name already exists in the same parent
            if ($request->has('name')) {
                $existingFolder = DocumentationFolder::where('company_id', $companyId)
                    ->where('name', $request->name)
                    ->where('parent_id', $parentId ?? $folder->parent_id)
                    ->where('id', '!=', $folder->id)
                    ->first();

                if ($existingFolder) {
                    return response()->json([
                        'success' => false,
                        'message' => 'A folder with this name already exists in the selected location.'
                    ], 422);
                }
            }

            // Update folder with processed data
            $updateData = $request->only(['name', 'description', 'access_level']);
            if ($request->has('parent_id')) {
                $updateData['parent_id'] = $parentId;
            }
            $folder->update($updateData);
            $folder->load(['creator', 'parent']);

            return response()->json([
                'success' => true,
                'data' => $folder,
                'message' => 'Folder updated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to update folder: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update folder.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a folder
     */
    public function deleteFolder($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $folder = DocumentationFolder::forCompany($companyId)->findOrFail($id);

            // Check if folder has children
            if ($folder->children()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete folder that contains subfolders. Please delete subfolders first.'
                ], 422);
            }

            // Check if folder has documents
            if ($folder->documents()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete folder that contains documents. Please move or delete documents first.'
                ], 422);
            }

            $folder->delete();

            return response()->json([
                'success' => true,
                'message' => 'Folder deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to delete folder: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete folder.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download a document
     */
    public function download($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $document = Documentation::where('company_id', $companyId)->findOrFail($id);

            if (!Storage::disk('public')->exists($document->file_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found.'
                ], 404);
            }

            return Storage::disk('public')->download($document->file_path, $document->file_name);
        } catch (\Exception $e) {
            Log::error("Failed to download document: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to download document.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve/Reject a document
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $validator = \Validator::make($request->all(), [
                'status' => 'required|string|in:approved,rejected',
                'comments' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $document = Documentation::where('company_id', $companyId)->findOrFail($id);

            $document->update([
                'status' => $request->status,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'approval_comments' => $request->comments
            ]);

            return response()->json([
                'success' => true,
                'data' => $document,
                'message' => 'Document status updated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to update document status: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update document status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
