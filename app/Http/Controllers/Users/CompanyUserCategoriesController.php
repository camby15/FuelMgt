<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\UserCategory;
use App\Models\User;
use Illuminate\Support\Facades\{Log, Auth, Session, DB, Validator};
use Illuminate\Http\{JsonResponse, Request, Response, RedirectResponse};
use Illuminate\View\View;

class CompanyUserCategoriesController extends Controller
{
    /**
     * Display a listing of the user categories.
     */
    public function index(): View
    {
        try {
            if (!Auth::check()) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                Log::warning('No company ID in session');
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Company session expired. Please login again.');
            }

            // Get the company user
            $companyUser = User::with('companyProfile')->find($companyId);

            // Get all categories for this company
            $categories = UserCategory::where('company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('company.user-management.user-category', [
                'categories' => $categories,
                'company' => $companyUser->companyProfile
            ]);

        } catch (\Exception $e) {
            Log::error('Error in index method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Error loading categories. Please try again.');
        }
    }

    /**
     * Store a newly created user category.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|string|in:active,inactive'
            ]);

            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            // Check if category with same name and status exists
            $existingCategory = UserCategory::where('company_id', $companyId)
                ->where('name', $request->name)
                ->where('status', $request->status === 'active')
                ->first();

            if ($existingCategory) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'A category with this name and status already exists.'
                ], 422);
            }

            $category = UserCategory::create([
                'name' => $request->name,
                'description' => $request->description,
                'company_id' => $companyId,
                'status' => $request->status === 'active'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Category created successfully',
                'data' => $category
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating category', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error creating category. Please try again.'
            ], 500);
        }
    }

    /**
     * Update the specified user category.
     */
    public function update(Request $request, UserCategory $category): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|string|in:active,inactive'
            ]);

            $companyId = Session::get('selected_company_id');
            
            if (!$companyId || $category->company_id !== $companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access'
                ], 401);
            }

            $category->update([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status === 'active'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully',
                'data' => $category
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating category', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error updating category. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove the specified user category.
     */
    public function destroy(UserCategory $category): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId || $category->company_id !== $companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access'
                ], 401);
            }

            $category->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting category', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting category. Please try again.'
            ], 500);
        }
    }

    /**
     * Generate a CSV template for bulk category upload
     */
    public function downloadTemplate()
    {
        // Create a temporary file
        $filename = 'User Categories Bulk Upload Template.csv';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);

        // Open the file for writing
        $handle = fopen($tempFile, 'w');

        // Write the header row
        $headers = ['Name', 'Description', 'Status'];
        fputcsv($handle, $headers);

        // Write an example row
        $exampleRow = [
            'Sales Department',
            'Category for sales team members',
            'active'
        ];
        fputcsv($handle, $exampleRow);

        // Close the file
        fclose($handle);

        // Return the file for download
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Bulk upload categories from a CSV file
     */
    public function bulkUpload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'bulk_upload_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login to continue'
            ], 401);
        }

        // Get the company ID from the session
        $companyId = Session::get('selected_company_id');
        if (!$companyId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Company session expired'
            ], 401);
        }

        try {
            // Open the uploaded file
            $file = $request->file('bulk_upload_file');
            $handle = fopen($file->getPathname(), 'r');
            
            // Read the header row
            $headers = fgetcsv($handle);
            
            // Validate headers
            $requiredHeaders = ['Name', 'Description', 'Status'];
            foreach ($requiredHeaders as $requiredHeader) {
                if (!in_array($requiredHeader, $headers)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Missing required column: $requiredHeader"
                    ], 400);
                }
            }

            // Prepare tracking variables
            $successCount = 0;
            $failedCategories = [];

            // Process each row
            while (($row = fgetcsv($handle)) !== false) {
                // Create an associative array from the row
                $categoryData = array_combine($headers, $row);

                // Validate category data
                $validator = Validator::make($categoryData, [
                    'Name' => 'required|string|max:255',
                    'Description' => 'nullable|string',
                    'Status' => 'required|in:active,inactive'
                ]);

                // If validation fails, log the error and continue
                if ($validator->fails()) {
                    $failedCategories[] = [
                        'data' => $categoryData,
                        'errors' => $validator->errors()->all()
                    ];
                    continue;
                }

                // Check for existing category with same name and status
                $existingCategory = UserCategory::where('company_id', $companyId)
                    ->where('name', $categoryData['Name'])
                    ->where('status', $categoryData['Status'] === 'active')
                    ->first();

                if ($existingCategory) {
                    $failedCategories[] = [
                        'data' => $categoryData,
                        'errors' => ['A category with this name and status already exists.']
                    ];
                    continue;
                }

                // Create the category
                $category = new UserCategory();
                $category->company_id = $companyId;
                $category->name = $categoryData['Name'];
                $category->description = $categoryData['Description'];
                $category->status = $categoryData['Status'] === 'active';
                $category->save();

                $successCount++;
            }

            // Close the file
            fclose($handle);

            // Prepare response message
            $message = "Successfully imported $successCount categories.";
            if (!empty($failedCategories)) {
                $message .= " " . count($failedCategories) . " categories failed to import.";
                // Log failed imports for admin review
                Log::warning('Bulk category import partial failure', ['failed_imports' => $failedCategories]);
            }

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'failedCategories' => $failedCategories
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk upload error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Bulk upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the status of a category.
     */
    public function toggleStatus(UserCategory $category): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            // Check if category belongs to the company
            if ($category->company_id !== $companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            // Check if another category with same name and new status exists
            $existingCategory = UserCategory::where('company_id', $companyId)
                ->where('name', $category->name)
                ->where('status', !$category->status)
                ->first();

            if ($existingCategory) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'A category with this name and status already exists.'
                ], 422);
            }

            // Toggle the status
            $category->status = !$category->status;
            $category->save();

            $newStatus = $category->status ? 'active' : 'inactive';

            return response()->json([
                'status' => 'success',
                'message' => "Category status changed to {$newStatus}",
                'data' => $category
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling category status', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error changing category status. Please try again.'
            ], 500);
        }
    }
}