<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HRJob;
use App\Models\JobApplication;
use App\Models\Onboarding;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class JobsController extends Controller
{
    public function all(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');

            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $jobs = HRJob::where('company_id', $companyId)
                         ->whereNull('deleted_at')
                         ->get();

            return response()->json([
                'success' => true,
                'data' => $jobs,
                'message' => 'Jobs retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching jobs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch jobs',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    


    public function getStatusCounts(Request $request)
{
    try {
        $companyId = Session::get('selected_company_id');

        if (!$companyId) {
            return response()->json([
                'success' => false,
                'message' => 'No company selected.'
            ], 400);
        }

        $statuses = ['draft', 'open', 'interviewing', 'offered', 'onboarded', 'closed'];

        $counts = HRJob::where('company_id', $companyId)
            ->whereNull('deleted_at')
            ->selectRaw("status, COUNT(*) as count")
            ->whereIn('status', $statuses)
            ->groupBy('status')
            ->pluck('count', 'status');

        // Ensure all statuses are present, even if count is 0
        $statusCounts = [];
        foreach ($statuses as $status) {
            $statusCounts[$status] = $counts[$status] ?? 0;
        }

        return response()->json([
            'success' => true,
            'data' => $statusCounts,
        ]);
    } catch (\Exception $e) {
        Log::error('Error fetching status counts: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to fetch status counts',
        ], 500);
    }
}



    public function store(Request $request)
    {
    if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
        return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
    }

    $companyId = Session::get('selected_company_id');
    if (!$companyId) {
        return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
    }

    $userId = Auth::id(); // If you want to use later or add to migration/model

    $rules = [
        'title' => 'required|string|max:255',
        'department' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'status' => 'required|string|in:draft,open,closed',
        'posted_date' => 'required|date',
        'description' => 'required|string',
        'requirements' => 'required|string',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $errors = [];
        foreach ($rules as $field => $rule) {
            if ($validator->errors()->has($field)) {
                $errors[] = $validator->errors()->first($field);
            }
        }
        return response()->json([
            'success' => false,
            'errors' => $errors
        ], 422);
    }

    try {
        $job = HRJob::create([
            'company_id' => $companyId,
            'user_id' => $userId,
            'token' => Str::uuid()->toString(),
            'title' => $request->title,
            'department' => $request->department,
            'location' => $request->location,
            'type' => $request->type,
            'status' => $request->status,
            'posted_date' => $request->posted_date,
            'applications' => 0,
            'description' => $request->description,
            'requirements' => $request->requirements,
            // Optional: 'user_id' => $userId, if you add it later
        ]);

        return response()->json([
            'success' => true,
            'data' => $job,
            'message' => 'Job created successfully',
            'shareable_link' => url("/job?token={$job->token}")
        ], 201);
    } catch (\Exception $e) {
        Log::error('Error creating job: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to create job',
            'message' => $e->getMessage()
        ], 500);
    }
}

    
    public function showByToken(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');

            $rules = ['token' => 'required|string'];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errors = [];
                foreach ($rules as $field => $rule) {
                    if ($validator->errors()->has($field)) {
                        $errors[] = $validator->errors()->first($field);
                    }
                }
                return response()->json([
                    'success' => false,
                    'errors' => $errors
                ], 422);
            }

            $job = HRJob::where('company_id', $companyId)
                        ->where('token', $request->token)
                        ->whereNull('deleted_at')
                        ->first();

            if (!$job) {
                return response()->json([
                    'success' => false,
                    'error' => 'Job not found',
                    'message' => 'Job not found or has been deleted'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $job,
                'message' => 'Job retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching job by token: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch job',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');

            $rules = [
                'id' => 'required|integer|exists:hrjobrecruitment,id',
                'title' => 'required|string|max:255',
                'department' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'description' => 'required|string',
                'requirements' => 'required|string',
                'status' => 'required|in:draft,open,closed'
                
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errors = [];
                foreach ($rules as $field => $rule) {
                    if ($validator->errors()->has($field)) {
                        $errors[] = $validator->errors()->first($field);
                    }
                }
                return response()->json([
                    'success' => false,
                    'errors' => $errors
                ], 422);
            }

            $job = HRJob::where('company_id', $companyId)
                        ->whereNull('deleted_at')
                        ->find($request->id);

            if (!$job) {
                return response()->json([
                    'success' => false,
                    'error' => 'Job not found',
                    'message' => 'Job not found or has been deleted'
                ], 404);
            }

            $job->update([
                'title' => $request->title,
                'department' => $request->department,
                'location' => $request->location,
                'type' => $request->type,
                'description' => $request->description,
                'requirements' => $request->requirements,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'data' => $job,
                'message' => 'Job updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating job: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to update job',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');

            $rules = ['id' => 'required|integer|exists:hrjobrecruitment,id'];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errors = [];
                foreach ($rules as $field => $rule) {
                    if ($validator->errors()->has($field)) {
                        $errors[] = $validator->errors()->first($field);
                    }
                }
                return response()->json([
                    'success' => false,
                    'errors' => $errors
                ], 422);
            }

            $job = HRJob::where('company_id', $companyId)
                        ->whereNull('deleted_at')
                        ->find($request->id);

            if (!$job) {
                return response()->json([
                    'success' => false,
                    'error' => 'Job not found',
                    'message' => 'Job not found or has been deleted'
                ], 404);
            }

            $job->delete();

            return response()->json([
                'success' => true,
                'message' => 'Job deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting job: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete job',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function showExternalApplicationForm(Request $request)
    {
        try {
            $token = $request->query('token');

            if (!$token) {
                abort(404, 'Job application token is required.');
            }

            $job = HRJob::where('token', $token)
                       ->whereNull('deleted_at')
                       ->first();

            if (!$job) {
                abort(404, 'Job application not found or no longer available.');
            }

            return view('external.job-application', compact('job'));
        } catch (\Exception $e) {
            Log::error('Error showing external application form: ' . $e->getMessage());
            abort(500, 'An error occurred while loading the application form.');
        }
    }

    public function submitExternalApplication(Request $request)
    {
        try {
            $rules = [
                'job_id' => 'required|exists:hrjobrecruitment,id',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'date_of_birth' => 'nullable|date|before:today',
                'gender' => 'nullable|in:male,female',
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:255',
                'region' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'nationality' => 'nullable|string|max:255',
                'education_level' => 'nullable|string|max:255',
                'experience_years' => 'nullable|integer|min:0|max:50',
                'cover_letter' => 'nullable|string|max:2000',
                'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120' // 5MB max
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // Check if job is still open
            $job = HRJob::find($request->job_id);
            if (!$job || $job->status !== 'open') {
                return back()->with('error', 'This job application is no longer available.');
            }

            // Handle file upload
            $resumePath = null;
            if ($request->hasFile('resume')) {
                $resumePath = $request->file('resume')->store('resumes', 'public');
            }

            // Create application
            JobApplication::create([
                'job_id' => $request->job_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
                'city' => $request->city,
                'region' => $request->region,
                'country' => $request->country,
                'nationality' => $request->nationality,
                'education_level' => $request->education_level,
                'experience_years' => $request->experience_years,
                'cover_letter' => $request->cover_letter,
                'resume_path' => $resumePath,
                'applied_at' => now()
            ]);

            // Update job applications count
            $job->increment('applications');

            return back()->with('success', 'Your application has been submitted successfully! We will contact you soon.');
        } catch (\Exception $e) {
            Log::error('Error submitting external application: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while submitting your application. Please try again.');
        }
    }

    public function getApplications(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');

            $applications = JobApplication::with('job')
                ->whereHas('job', function($query) use ($companyId) {
                    $query->where('company_id', $companyId)
                          ->whereNull('deleted_at');
                })
                ->orderBy('applied_at', 'desc')
                ->get()
                ->map(function($application) {
                    return [
                        'id' => $application->id,
                        'first_name' => $application->first_name,
                        'last_name' => $application->last_name,
                        'email' => $application->email,
                        'phone' => $application->phone,
                        'experience_years' => $application->experience_years,
                        'applied_at' => $application->applied_at,
                        'status' => $application->status ?? 'new',
                        'job' => $application->job ? [
                            'id' => $application->job->id,
                            'title' => $application->job->title
                        ] : null
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $applications
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching applications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch applications'
            ], 500);
        }
    }

    public function getApplicationDetails(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');

            $application = JobApplication::with('job')
                ->whereHas('job', function($query) use ($companyId) {
                    $query->where('company_id', $companyId)
                          ->whereNull('deleted_at');
                })
                ->find($id);

            if (!$application) {
                return response()->json([
                    'success' => false,
                    'error' => 'Application not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $application->id,
                    'first_name' => $application->first_name,
                    'last_name' => $application->last_name,
                    'email' => $application->email,
                    'phone' => $application->phone,
                    'date_of_birth' => $application->date_of_birth,
                    'gender' => $application->gender,
                    'address' => $application->address,
                    'city' => $application->city,
                    'region' => $application->region,
                    'country' => $application->country,
                    'nationality' => $application->nationality,
                    'education_level' => $application->education_level,
                    'experience_years' => $application->experience_years,
                    'cover_letter' => $application->cover_letter,
                    'resume_path' => $application->resume_path,
                    'applied_at' => $application->applied_at,
                    'status' => $application->status ?? 'new',
                    'job' => $application->job ? [
                        'id' => $application->job->id,
                        'title' => $application->job->title,
                        'department' => $application->job->department
                    ] : null
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching application details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch application details'
            ], 500);
        }
    }

    public function rejectApplication(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');

            $application = JobApplication::whereHas('job', function($query) use ($companyId) {
                $query->where('company_id', $companyId)
                      ->whereNull('deleted_at');
            })->find($id);

            if (!$application) {
                return response()->json([
                    'success' => false,
                    'error' => 'Application not found'
                ], 404);
            }

            $application->update(['status' => 'rejected']);

            return response()->json([
                'success' => true,
                'message' => 'Application rejected successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error rejecting application: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to reject application'
            ], 500);
        }
    }

    // Onboarding Methods
    public function getOnboardingData(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');

            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $onboardingData = Onboarding::with(['employee.personalInfo', 'employee.employmentInfo', 'manager.personalInfo'])
                ->whereHas('employee', function($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })
                ->orderBy('start_date', 'desc')
                ->get()
                ->map(function($onboarding) {
                    return [
                        'id' => $onboarding->id,
                        'employee' => [
                            'id' => $onboarding->employee->id,
                            'staff_id' => $onboarding->employee->staff_id,
                            'first_name' => $onboarding->employee->personalInfo->first_name ?? '',
                            'last_name' => $onboarding->employee->personalInfo->last_name ?? '',
                            'email' => $onboarding->employee->email,
                            'position' => $onboarding->employee->employmentInfo->position ?? '',
                            'department' => $onboarding->employee->employmentInfo->department ?? ''
                        ],
                        'start_date' => $onboarding->start_date,
                        'offer_accepted_date' => $onboarding->offer_accepted_date,
                        'documents_uploaded_status' => $onboarding->documents_uploaded_status,
                        'documents_uploaded_date' => $onboarding->documents_uploaded_date,
                        'staff_id_assigned_status' => $onboarding->staff_id_assigned_status,
                        'staff_id_assigned_date' => $onboarding->staff_id_assigned_date,
                        'first_day_checklist_status' => $onboarding->first_day_checklist_status,
                        'first_day_checklist_date' => $onboarding->first_day_checklist_date,
                        'overall_status' => $onboarding->overall_status,
                        'progress_percentage' => $onboarding->getProgressPercentage(),
                        'completed_tasks' => $onboarding->getCompletedTasksCount(),
                        'manager' => $onboarding->manager ? [
                            'first_name' => $onboarding->manager->personalInfo->first_name ?? '',
                            'last_name' => $onboarding->manager->personalInfo->last_name ?? ''
                        ] : null
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $onboardingData
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching onboarding data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch onboarding data'
            ], 500);
        }
    }

    public function createOnboarding(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');

            $rules = [
                'employee_id' => 'required|exists:employees,id',
                'start_date' => 'required|date',
                'manager_id' => 'nullable|exists:employees,id'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all()
                ], 422);
            }

            // Check if employee already has onboarding record
            $existingOnboarding = Onboarding::where('employee_id', $request->employee_id)->first();
            if ($existingOnboarding) {
                return response()->json([
                    'success' => false,
                    'error' => 'Employee already has an onboarding record'
                ], 400);
            }

            $onboarding = Onboarding::create([
                'employee_id' => $request->employee_id,
                'start_date' => $request->start_date,
                'manager_id' => $request->manager_id,
                'overall_status' => 'not_started'
            ]);

            return response()->json([
                'success' => true,
                'data' => $onboarding,
                'message' => 'Onboarding record created successfully'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating onboarding record: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to create onboarding record'
            ], 500);
        }
    }

    public function updateOnboardingStatus(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');

            $rules = [
                'step' => 'required|in:offer_accepted,documents_uploaded,staff_id_assigned,first_day_checklist',
                'status' => 'required|in:completed,pending,in_progress',
                'date' => 'nullable|date'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all()
                ], 422);
            }

            $onboarding = Onboarding::whereHas('employee', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->find($id);

            if (!$onboarding) {
                return response()->json([
                    'success' => false,
                    'error' => 'Onboarding record not found'
                ], 404);
            }

            $step = $request->step;
            $status = $request->status;
            $date = $request->date;

            // Update the specific step
            switch ($step) {
                case 'offer_accepted':
                    $onboarding->offer_accepted_date = $date;
                    break;
                case 'documents_uploaded':
                    $onboarding->documents_uploaded_status = $status;
                    $onboarding->documents_uploaded_date = $date;
                    break;
                case 'staff_id_assigned':
                    $onboarding->staff_id_assigned_status = $status;
                    $onboarding->staff_id_assigned_date = $date;
                    break;
                case 'first_day_checklist':
                    $onboarding->first_day_checklist_status = $status;
                    $onboarding->first_day_checklist_date = $date;
                    break;
            }

            // Update overall status based on progress
            $progress = $onboarding->getProgressPercentage();
            if ($progress == 100) {
                $onboarding->overall_status = 'completed';
            } elseif ($progress > 0) {
                $onboarding->overall_status = 'in_progress';
            }

            $onboarding->save();

            return response()->json([
                'success' => true,
                'data' => $onboarding,
                'message' => 'Onboarding status updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating onboarding status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to update onboarding status'
            ], 500);
        }
    }

    public function deleteOnboarding(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');

            $onboarding = Onboarding::whereHas('employee', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->find($id);

            if (!$onboarding) {
                return response()->json([
                    'success' => false,
                    'error' => 'Onboarding record not found'
                ], 404);
            }

            $onboarding->delete();

            return response()->json([
                'success' => true,
                'message' => 'Onboarding record deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting onboarding record: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete onboarding record'
            ], 500);
        }
    }
}
