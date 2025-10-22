<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HrEmploymentPersonalInfo;
use App\Models\HrEmploymentEmploymentInfo;
use App\Models\HrEmploymentBankInfo;
use App\Models\HrEmploymentEmergencyContact;
use App\Models\HrEmploymentDocuments;
use App\Models\Performance;
use App\Models\Training;
use App\Models\Leave;
use App\Models\ExpenseClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class StaffController extends Controller
{
    /**
     * Display the staff self-service portal
     */
    public function index()
    {
        $user = Auth::user();
        $companyId = Session::get('selected_company_id');
        
        // Get employee data with relationships
        $employee = Employee::with([
            'personalInfo',
            'employmentInfo', 
            'bankInfo',
            'emergencyContact',
            'documents'
        ])->where('user_id', $user->id)
          ->where('company_id', $companyId)
          ->first();

        // Get dashboard stats
        $dashboardStats = $this->getDashboardStats($user->id, $companyId);

        return view('company.HumanResource.staff', compact('employee', 'dashboardStats'));
    }

    /**
     * Get dashboard statistics for the staff portal
     */
    private function getDashboardStats($userId, $companyId)
    {
        $employee = Employee::where('user_id', $userId)
                          ->where('company_id', $companyId)
                          ->first();

        if (!$employee) {
            return [
                'leave_days_remaining' => 0,
                'assigned_tasks' => 0,
                'upcoming_trainings' => 0,
                'new_documents' => 0
            ];
        }

        // Calculate leave days remaining (assuming 21 days annual leave)
        $currentYear = Carbon::now()->year;
        $leaveTaken = Leave::where('employee_id', $employee->id)
                          ->where('status', 'approved')
                          ->whereYear('start_date', $currentYear)
                          ->sum('days_requested');
        
        $leaveEntitlement = 21; // Default annual leave
        $leaveDaysRemaining = max(0, $leaveEntitlement - $leaveTaken);

        // Get assigned tasks (from performance or project management)
        $assignedTasks = Performance::where('employee_id', $employee->id)
                                  ->where('status', 'pending')
                                  ->count();

        // Get upcoming trainings
        $upcomingTrainings = Training::where('employee_id', $employee->id)
                                   ->where('status', 'scheduled')
                                   ->where('start_date', '>', Carbon::now())
                                   ->count();

        // Get new documents
        $newDocuments = HrEmploymentDocuments::where('employee_id', $employee->id)
                                           ->where('status', 'pending_review')
                                           ->count();

        return [
            'leave_days_remaining' => $leaveDaysRemaining,
            'assigned_tasks' => $assignedTasks,
            'upcoming_trainings' => $upcomingTrainings,
            'new_documents' => $newDocuments
        ];
    }

    /**
     * Update personal information
     */
    public function updatePersonalInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'personal_email' => 'nullable|email|max:255',
            'primary_phone' => 'nullable|string|max:20',
            'secondary_phone' => 'nullable|string|max:20',
            'residential_address' => 'nullable|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'emergency_contact_address' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $companyId = Session::get('selected_company_id');
            
            $employee = Employee::where('user_id', $user->id)
                              ->where('company_id', $companyId)
                              ->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee record not found'
                ], 404);
            }

            // Update personal info
            $personalInfo = HrEmploymentPersonalInfo::updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_name' => $request->middle_name,
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'personal_email' => $request->personal_email,
                    'primary_phone' => $request->primary_phone,
                    'secondary_phone' => $request->secondary_phone,
                    'residential_address' => $request->residential_address,
                ]
            );

            // Update emergency contact
            if ($request->emergency_contact_name) {
                HrEmploymentEmergencyContact::updateOrCreate(
                    ['employee_id' => $employee->id],
                    [
                        'name' => $request->emergency_contact_name,
                        'phone' => $request->emergency_contact_phone,
                        'relationship' => $request->emergency_contact_relationship,
                        'address' => $request->emergency_contact_address,
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Personal information updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating personal info: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating personal information'
            ], 500);
        }
    }

    /**
     * Get expense claims for the authenticated user
     */
    public function getExpenseClaims(Request $request)
    {
        $user = Auth::user();
        $companyId = Session::get('selected_company_id');
        
        $employee = Employee::where('user_id', $user->id)
                          ->where('company_id', $companyId)
                          ->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee record not found'
            ], 404);
        }

        $filter = $request->get('filter', 'all');
        
        $query = ExpenseClaim::where('employee_id', $employee->id);
        
        if ($filter !== 'all') {
            $query->where('status', $filter);
        }
        
        $claims = $query->orderBy('created_at', 'desc')
                       ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $claims
        ]);
    }

    /**
     * Get training data for the authenticated user
     */
    public function getTrainingData(Request $request)
    {
        $user = Auth::user();
        $companyId = Session::get('selected_company_id');
        
        $employee = Employee::where('user_id', $user->id)
                          ->where('company_id', $companyId)
                          ->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee record not found'
            ], 404);
        }

        // Get upcoming trainings
        $upcomingTrainings = Training::where('employee_id', $employee->id)
                                   ->where('status', 'scheduled')
                                   ->where('start_date', '>', Carbon::now())
                                   ->orderBy('start_date', 'asc')
                                   ->get();

        // Get required trainings
        $requiredTrainings = Training::where('employee_id', $employee->id)
                                   ->where('is_required', true)
                                   ->where('status', 'pending')
                                   ->orderBy('due_date', 'asc')
                                   ->get();

        // Get training history
        $trainingHistory = Training::where('employee_id', $employee->id)
                                 ->where('status', 'completed')
                                 ->orderBy('completion_date', 'desc')
                                 ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => [
                'upcoming' => $upcomingTrainings,
                'required' => $requiredTrainings,
                'history' => $trainingHistory
            ]
        ]);
    }

    /**
     * Get documents for the authenticated user
     */
    public function getDocuments(Request $request)
    {
        $user = Auth::user();
        $companyId = Session::get('selected_company_id');
        
        $employee = Employee::where('user_id', $user->id)
                          ->where('company_id', $companyId)
                          ->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee record not found'
            ], 404);
        }

        $documents = HrEmploymentDocuments::where('employee_id', $employee->id)
                                        ->orderBy('created_at', 'desc')
                                        ->get();

        return response()->json([
            'success' => true,
            'data' => $documents
        ]);
    }

    /**
     * Upload profile picture
     */
    public function uploadProfilePicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $companyId = Session::get('selected_company_id');
            
            $employee = Employee::where('user_id', $user->id)
                              ->where('company_id', $companyId)
                              ->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee record not found'
                ], 404);
            }

            // Store the uploaded file
            $file = $request->file('profile_image');
            $filename = 'profile_' . $employee->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('employee_profiles', $filename, 'public');

            // Update personal info with avatar path
            HrEmploymentPersonalInfo::updateOrCreate(
                ['employee_id' => $employee->id],
                ['avatar' => $path]
            );

            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated successfully',
                'avatar_url' => Storage::url($path)
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading profile picture: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading the profile picture'
            ], 500);
        }
    }
}
