<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HrEmploymentPersonalInfo;
use App\Models\HrEmploymentEmploymentInfo;
use App\Models\HrEmploymentBankInfo;
use App\Models\HrEmploymentEmergencyContact;
use App\Models\HrEmploymentDocuments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeMessageMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeController extends Controller
{
    private function generateNextStaffId($companyId)
    {
        $lastEmployee = Employee::withTrashed()
            ->where('company_id', $companyId)
            ->whereNotNull('staff_id')
            ->where('staff_id', 'like', 'GESL%')
            ->orderByRaw("CAST(SUBSTRING(staff_id, 5) AS UNSIGNED) DESC")
            ->first();

        if ($lastEmployee && preg_match('/GESL(\\d+)/', $lastEmployee->staff_id, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        // Ensure uniqueness even under concurrency
        while (true) {
            $candidate = 'GESL' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            $exists = Employee::withTrashed()
                ->where('company_id', $companyId)
                ->where('staff_id', $candidate)
                ->exists();
            if (!$exists) {
                return $candidate;
            }
            $nextNumber++;
        }
    }
    public function index()
    {
        return view('hr.employees.index');
    }

    public function downloadImportTemplate(): StreamedResponse
    {
        // Always generate a fresh template without staff_id (auto-generated)
        $headers = [
            'first_name','last_name','personal_email','primary_phone',
            'date_of_birth','gender','marital_status','nationality','country','region','city',
            'tin_number','tax_status','tax_exemption',
            'join_date','department','position','employment_type','probation_status','employment_status',
            'bank_name','branch_name','account_number','account_type','currency',
            'primary_emergency_name','primary_emergency_relation','primary_emergency_phone','primary_emergency_address'
        ];
        return response()->streamDownload(function () use ($headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            fclose($out);
        }, 'employee_import_template.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
            ]);

            $companyId = Session::get('selected_company_id');

            // For now, support CSV rows via native parsing. XLSX can be added with maatwebsite/excel if available
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension !== 'csv' && $extension !== 'txt') {
                return response()->json([
                    'success' => false,
                    'error' => 'Only CSV import is supported at the moment.',
                ], 422);
            }

            $handle = fopen($file->getRealPath(), 'r');
            if ($handle === false) {
                return response()->json(['success' => false, 'error' => 'Unable to read uploaded file'], 422);
            }

            $headers = fgetcsv($handle);
            if (!$headers) {
                fclose($handle);
                return response()->json(['success' => false, 'error' => 'Empty CSV or invalid header'], 422);
            }

            $normalized = array_map(function ($h) { return strtolower(trim($h)); }, $headers);
            $required = ['first_name','last_name','personal_email','primary_phone','join_date','department','position','employment_type'];
            foreach ($required as $field) {
                if (!in_array($field, $normalized, true)) {
                    fclose($handle);
                    return response()->json(['success' => false, 'error' => 'Missing required column: ' . $field], 422);
                }
            }

            $created = 0;
            $skipped = 0;
            $errors = [];
            DB::beginTransaction();
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) === 1 && trim($row[0]) === '') {
                    continue; // skip empty lines
                }
                $data = array_combine($normalized, array_map('trim', $row));

                // Basic validation per row (require complete personal info)
                $requiredPersonal = ['first_name','last_name','personal_email','primary_phone'];
                $missingPersonal = [];
                foreach ($requiredPersonal as $pf) {
                    if (!isset($data[$pf]) || $data[$pf] === '') {
                        $missingPersonal[] = $pf;
                    }
                }
                if (!empty($missingPersonal)) {
                    $skipped++;
                    $errors[] = 'Skipped row: missing personal fields [' . implode(', ', $missingPersonal) . '] for email ' . ($data['personal_email'] ?? 'unknown');
                    continue;
                }

                // Skip duplicates by email (including soft-deleted)
                $emailExists = Employee::withTrashed()
                    ->where('company_id', $companyId)
                    ->where('email', $data['personal_email'])
                    ->exists();
                if ($emailExists) {
                    $skipped++;
                    $errors[] = 'Skipped duplicate email: ' . $data['personal_email'];
                    continue;
                }

                try {
                    $employee = Employee::create([
                        'company_id' => $companyId,
                        'user_id' => Auth::id(),
                        'email' => $data['personal_email'],
                        'status' => $data['employment_status'] ?? 'active',
                    ]);

                // Staff ID generation includes soft-deleted and guarantees uniqueness
                $generatedStaffId = $this->generateNextStaffId($companyId);
                $employee->update(['staff_id' => $generatedStaffId]);

                HrEmploymentPersonalInfo::create([
                    'employee_id' => $employee->id,
                    'first_name' => $data['first_name'] ?? null,
                    'middle_name' => $data['middle_name'] ?? null,
                    'last_name' => $data['last_name'] ?? null,
                    'date_of_birth' => $data['date_of_birth'] ?? null,
                    'gender' => $data['gender'] ?? null,
                    'primary_phone' => $data['primary_phone'] ?? null,
                    'secondary_phone' => $data['secondary_phone'] ?? null,
                    'personal_email' => $data['personal_email'] ?? null,
                    'marital_status' => $data['marital_status'] ?? null,
                    'nationality' => $data['nationality'] ?? null,
                    'country' => $data['country'] ?? null,
                    'region' => $data['region'] ?? null,
                    'city' => $data['city'] ?? null,
                    'tin_number' => $data['tin_number'] ?? null,
                    'ssnit_number' => $data['ssnit_number'] ?? null,
                    'tax_status' => $data['tax_status'] ?? null,
                    'tax_exemption' => $data['tax_exemption'] ?? null,
                ]);

                // Normalize employment type and fill safe defaults
                $rawEmploymentType = strtolower(trim((string)($data['employment_type'] ?? '')));
                $employmentTypeMap = [
                    'full-time' => 'fixed_term',
                    'full_time' => 'fixed_term',
                    'full time' => 'fixed_term',
                    'permanent' => 'fixed_term',
                    'contract' => 'ind_contractors',
                    'contractor' => 'ind_contractors',
                    'ind contractors' => 'ind_contractors',
                    'ind_contractors' => 'ind_contractors',
                    'national service' => 'national_service',
                    'national_service' => 'national_service',
                ];
                $normalizedEmploymentType = $employmentTypeMap[$rawEmploymentType] ?? ($rawEmploymentType ?: 'fixed_term');

                $joinDate = $data['join_date'] ?? null;
                if (!$joinDate) {
                    $joinDate = now()->toDateString();
                }

                $department = $data['department'] ?? 'unassigned';
                $position = $data['position'] ?? 'unspecified';

                HrEmploymentEmploymentInfo::create([
                    'employee_id' => $employee->id,
                    'staff_id' => $generatedStaffId,
                    'join_date' => $joinDate,
                    'department' => $department,
                    'position' => $position,
                    'employment_type' => $normalizedEmploymentType,
                    'probation_status' => $data['probation_status'] ?? 'not_started',
                    'employment_status' => $data['employment_status'] ?? 'active',
                ]);

                HrEmploymentBankInfo::create([
                    'employee_id' => $employee->id,
                    'bank_name' => $data['bank_name'] ?? null,
                    'branch_name' => $data['branch_name'] ?? null,
                    'account_number' => $data['account_number'] ?? null,
                    'account_type' => $data['account_type'] ?? null,
                    'currency' => $data['currency'] ?? null,
                ]);

                HrEmploymentEmergencyContact::create([
                    'employee_id' => $employee->id,
                    'primary_emergency_name' => $data['primary_emergency_name'] ?? null,
                    'primary_emergency_relation' => $data['primary_emergency_relation'] ?? null,
                    'primary_emergency_phone' => $data['primary_emergency_phone'] ?? null,
                    'primary_emergency_address' => $data['primary_emergency_address'] ?? null,
                ]);

                    $created++;
                } catch (\Throwable $rowError) {
                    $skipped++;
                    $errors[] = 'Row error for ' . ($data['personal_email'] ?? 'unknown') . ': ' . $rowError->getMessage();
                    continue;
                }
            }
            fclose($handle);
            DB::commit();

            $message = "Imported {$created} employees";
            if ($skipped > 0) {
                $message .= ", skipped {$skipped}";
            }
            return response()->json([
                'success' => true,
                'message' => $message,
                'skipped' => $skipped,
                'errors' => $errors,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Employee import failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Import failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function getEmployees(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $query = Employee::where('company_id', $companyId)
                            ->with(['personalInfo', 'employmentInfo'])
                            ->when($request->search, function ($q) use ($request) {
                                $q->whereHas('personalInfo', function ($q) use ($request) {
                                    $q->where('first_name', 'like', "%{$request->search}%")
                                      ->orWhere('last_name', 'like', "%{$request->search}%")
                                      ->orWhere('personal_email', 'like', "%{$request->search}%");
                                })
                                ->orWhere('staff_id', 'like', "%{$request->search}%");
                            })
                            ->when($request->department, function ($q) use ($request) {
                                $q->whereHas('employmentInfo', function ($q) use ($request) {
                                    $q->where('department', $request->department);
                                });
                            })
                            ->when($request->sort_by, function ($q) use ($request) {
                                if ($request->sort_by == 'name_asc') {
                                    $q->join('hr_employment_personal_info', 'employees.id', '=', 'hr_employment_personal_info.employee_id')
                                      ->orderBy('hr_employment_personal_info.first_name', 'asc');
                                } elseif ($request->sort_by == 'name_desc') {
                                    $q->join('hr_employment_personal_info', 'employees.id', '=', 'hr_employment_personal_info.employee_id')
                                      ->orderBy('hr_employment_personal_info.first_name', 'desc');
                                } elseif ($request->sort_by == 'newest') {
                                    $q->orderBy('created_at', 'desc');
                                } elseif ($request->sort_by == 'oldest') {
                                    $q->orderBy('created_at', 'asc');
                                }
                            });

            $employees = $query->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $employees->items(),
                'pagination' => [
                    'current_page' => $employees->currentPage(),
                    'last_page' => $employees->lastPage(),
                    'per_page' => $employees->perPage(),
                    'total' => $employees->total(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching employees: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch employees',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getAvailableStaffIds()
    {
        try {
            $companyId = Session::get('selected_company_id');

            $nextStaffId = $this->generateNextStaffId($companyId);

            return response()->json([
                'success' => true,
                'data' => [
                    'next_staff_id' => $nextStaffId,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting available staff IDs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to get available staff IDs',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function create()
    {
        return view('hr.employees.create');
    }

    public function store(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $userId = Auth::id();

            $rules = [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'personal_email' => 'required|email|unique:employees,email',
               
                'primary_phone' => 'required|string',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female',
                'marital_status' => 'required|in:single,married,divorced,widowed',
                'nationality' => 'required|string',
                'country' => 'required|string',
                'region' => 'required|string',
                'city' => 'required|string',
                'tin_number' => 'required|string',
                'tax_status' => 'required|in:resident,non-resident',
                'tax_exemption' => 'required|in:none,disabled,dependent,other',
                'profile_picture' => 'nullable|image|max:5120',
                'join_date' => 'required|date',
                'department' => 'required|string',
                'position' => 'required|string',
                'employment_type' => 'required|in:fixed_term,ind_contractors,national_service',
                'probation_status' => 'required|in:not_started,in_progress,completed,extended',
                'employment_status' => 'required|in:active,on_leave,suspended,resigned,terminated',
               
               

                'bank_name' => 'nullable|string',
                'branch_name' => 'nullable|string',
                'account_number' => 'nullable|string',
                'account_type' => 'nullable|in:savings,current,fixed,dollar,euro,business',
                'currency' => 'nullable|string',

                'primary_emergency_name' => 'nullable|string',
                'primary_emergency_relation' => 'nullable|string',
                'primary_emergency_phone' => 'nullable|string',
                'primary_emergency_address' => 'nullable|string',

                'secondary_emergency_name' => 'nullable|string',
                'secondary_emergency_relation' => 'nullable|string',
                'secondary_emergency_phone' => 'nullable|string',
                'secondary_emergency_address' => 'nullable|string',

                'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'educational_certificate' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
                'other_documents.*' => 'nullable|file|max:5120',
                'documents_complete' => 'nullable|boolean',
                'documents_verified' => 'nullable|boolean',
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
                    'errors' => $errors,
                ], 422);
            }

            DB::beginTransaction();

            $employee = Employee::create([
                'company_id' => $companyId,
                'user_id' => $userId,
                'email' => $request->personal_email,
                'status' => $request->employment_status,
            ]);

            // Generate staff ID including soft-deleted, ensuring uniqueness
            $generatedStaffId = $this->generateNextStaffId($companyId);

            $employee->update([
                'staff_id' => $generatedStaffId,
            ]);

            $profilePicturePath = $request->file('profile_picture') ? $request->file('profile_picture')->store('employees/profile_pictures', 'public') : null;

            HrEmploymentPersonalInfo::create([
                'employee_id' => $employee->id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'primary_phone' => $request->primary_phone,
                'secondary_phone' => $request->secondary_phone,
                'personal_email' => $request->personal_email,
                'marital_status' => $request->marital_status,
                'nationality' => $request->nationality,
                'country' => $request->country,
                'region' => $request->region,
                'city' => $request->city,
                'id_type_1' => $request->id_type_1,
                'id_number_1' => $request->id_number_1,
                'id_type_2' => $request->id_type_2,
                'id_number_2' => $request->id_number_2,
                'id_notes' => $request->id_notes,
                'tin_number' => $request->tin_number,
                'ssnit_number' => $request->ssnit_number,
                'tax_status' => $request->tax_status,
                'tax_exemption' => $request->tax_exemption,
                'tax_notes' => $request->tax_notes,
                'profile_picture' => $profilePicturePath,
            ]);

            HrEmploymentEmploymentInfo::create([
                'employee_id' => $employee->id,
                'staff_id' =>  $generatedStaffId,
                'join_date' => $request->join_date,
                'department' => $request->department,
                'position' => $request->position,
                'supervisor_id' => $request->supervisor_id,
                'employment_type' => $request->employment_type,
                'probation_status' => $request->probation_status,
                'employment_status' => $request->employment_status,
            ]);

            $bankStatementPath = $request->file('bank_statement') ? $request->file('bank_statement')->store('employees/bank_statements', 'public') : null;

            HrEmploymentBankInfo::create([
                'employee_id' => $employee->id,
                'bank_name' => $request->bank_name,
                'branch_name' => $request->branch_name,
                'account_number' => $request->account_number,
                'account_type' => $request->account_type,
                'currency' => $request->currency,
                'ezwich_number' => $request->ezwich_number,
                'mobile_network' => $request->mobile_network,
                'mobile_number' => $request->mobile_number,
                'mobile_name' => $request->mobile_name,
                'bank_statement' => $bankStatementPath,
                'verification_status' => 'pending',
                'bank_notes' => $request->bank_notes,
            ]);

            HrEmploymentEmergencyContact::create([
                'employee_id' => $employee->id,
                'primary_emergency_name' => $request->primary_emergency_name,
                'primary_emergency_relation' => $request->primary_emergency_relation,
                'primary_emergency_phone' => $request->primary_emergency_phone,
                'primary_emergency_email' => $request->primary_emergency_email,
                'primary_emergency_alt_phone' => $request->primary_emergency_alt_phone,
                'primary_emergency_address' => $request->primary_emergency_address,
                'secondary_emergency_name' => $request->secondary_emergency_name,
                'secondary_emergency_relation' => $request->secondary_emergency_relation,
                'secondary_emergency_phone' => $request->secondary_emergency_phone,
                'secondary_emergency_email' => $request->secondary_emergency_email,
                'secondary_emergency_alt_phone' => $request->secondary_emergency_alt_phone,
                'secondary_emergency_address' => $request->secondary_emergency_address,
                'blood_group' => $request->blood_group,
                'nhis_number' => $request->nhis_number,
                'known_allergies' => $request->known_allergies,
                'allergy_details' => $request->allergy_details,
                'medical_conditions' => $request->medical_conditions,
                'special_needs' => $request->special_needs,
            ]);

            $otherDocuments = [];
            if ($request->hasFile('other_documents')) {
                foreach ($request->file('other_documents') as $file) {
                    $otherDocuments[] = $file->store('employees/other_documents', 'public');
                }
            }

            HrEmploymentDocuments::create([
                'employee_id' => $employee->id,
                'resume' => $request->file('resume') ? $request->file('resume')->store('employees/resumes', 'public') : null,
                'cover_letter' => $request->file('cover_letter') ? $request->file('cover_letter')->store('employees/cover_letters', 'public') : null,
                'educational_certificate' => $request->file('educational_certificate') ? $request->file('educational_certificate')->store('employees/certificates', 'public') : null,
                'other_documents' => $otherDocuments,
                'document_notes' => $request->document_notes,
                'documents_complete' => $request->documents_complete ?? false,
                'documents_verified' => $request->documents_verified ?? false,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Employee added successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding employee: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to add employee',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $employee = Employee::where('company_id', $companyId)
                               ->with(['personalInfo', 'employmentInfo', 'bankInfo', 'emergencyContact', 'documents'])
                               ->findOrFail($id);

            return response()->json(['success' => true, 'data' => $employee]);
        } catch (\Exception $e) {
            Log::error('Error fetching employee: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Employee not found',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function edit($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $employee = Employee::where('company_id', $companyId)
                               ->with(['personalInfo', 'employmentInfo', 'bankInfo', 'emergencyContact', 'documents'])
                               ->findOrFail($id);
            return view('hr.employees.edit', compact('employee'));
        } catch (\Exception $e) {
            Log::error('Error fetching employee for edit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Employee not found',
                'message' => $e->getMessage(),
            ], 404);
        }
    }




   
    public function update(Request $request, $id)
{
    try {
        $companyId = Session::get('selected_company_id');
        $employee = Employee::where('company_id', $companyId)->findOrFail($id);

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'personal_email' => 'required|email|unique:employees,email,' . $employee->id,
            'primary_phone' => 'required|string',
            'department' => 'required|string',
            'position' => 'required|string',
            'employment_type' => 'required|in:fixed_term,ind_contractors,national_service',
            'join_date' => 'required|date',
            'primary_emergency_name' => 'required|string',
            'primary_emergency_relation' => 'required|string',
            'primary_emergency_phone' => 'required|string',
            'primary_emergency_address' => 'required|string',
            'bank_name' => 'nullable|string',
            'branch_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'account_type' => 'nullable|in:savings,current,fixed,dollar,euro,business',
            'currency' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'educational_certificate' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'other_documents.*' => 'nullable|file|max:5120',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        DB::beginTransaction();

        // Update core employee email
        $employee->update([
            'email' => $request->personal_email,
        ]);

        // Update personal info
        $employee->personalInfo()->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'primary_phone' => $request->primary_phone,
            'secondary_phone' => $request->secondary_phone,
            'personal_email' => $request->personal_email,
            'nationality' => $request->nationality,
            'country' => $request->country,
            'region' => $request->region,
            'city' => $request->city,
            'id_type_1' => $request->id_type_1,
            'id_number_1' => $request->id_number_1,
            'id_type_2' => $request->id_type_2,
            'id_number_2' => $request->id_number_2,
            'tin_number' => $request->tin_number,
            'tax_status' => $request->tax_status,
            'tax_exemption' => $request->tax_exemption,
            'tax_notes' => $request->tax_notes,
        ]);

        // Update employment info
        $employee->employmentInfo()->update([
            'department' => $request->department,
            'position' => $request->position,
            'employment_type' => $request->employment_type,
            'join_date' => $request->join_date,
            'probation_status' => $request->probation_status,
            'employment_status' => $request->employment_status,
        ]);

        // Update emergency contact
        $employee->emergencyContact()->update([
            'primary_emergency_name' => $request->primary_emergency_name,
            'primary_emergency_relation' => $request->primary_emergency_relation,
            'primary_emergency_phone' => $request->primary_emergency_phone,
            'primary_emergency_address' => $request->primary_emergency_address,
        ]);

        // Update bank info
        if ($employee->bankInfo) {
            $employee->bankInfo()->update([
                'bank_name' => $request->bank_name,
                'branch_name' => $request->branch_name,
                'account_number' => $request->account_number,
                'account_type' => $request->account_type,
                'currency' => $request->currency,
            ]);
        } else {
            HrEmploymentBankInfo::create([
                'employee_id' => $employee->id,
                'bank_name' => $request->bank_name,
                'branch_name' => $request->branch_name,
                'account_number' => $request->account_number,
                'account_type' => $request->account_type,
                'currency' => $request->currency,
            ]);
        }

        // Update documents
        $docData = [];
        if ($request->file('resume')) {
            $docData['resume'] = $request->file('resume')->store('employees/resumes', 'public');
        }
        if ($request->file('cover_letter')) {
            $docData['cover_letter'] = $request->file('cover_letter')->store('employees/cover_letters', 'public');
        }
        if ($request->file('educational_certificate')) {
            $docData['educational_certificate'] = $request->file('educational_certificate')->store('employees/certificates', 'public');
        }
        if ($request->hasFile('other_documents')) {
            $otherDocs = [];
            foreach ($request->file('other_documents') as $file) {
                $otherDocs[] = $file->store('employees/other_documents', 'public');
            }
            $docData['other_documents'] = $otherDocs;
        }
        if (!empty($docData)) {
            if ($employee->documents) {
                $employee->documents()->update($docData);
            } else {
                $docData['employee_id'] = $employee->id;
                HrEmploymentDocuments::create($docData);
            }
        }

        DB::commit();

        return response()->json(['success' => true, 'message' => 'Employee updated successfully']);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error updating employee (modal): ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to update employee',
            'message' => $e->getMessage(),
        ], 500);
    }
}


    public function destroy($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $employee = Employee::where('company_id', $companyId)->findOrFail($id);
            $employee->delete();

            return response()->json(['success' => true, 'message' => 'Employee deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting employee: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete employee',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

public function sendMessage(Request $request, $id)
{
    try {
        $companyId = Session::get('selected_company_id');
        $employee = Employee::where('company_id', $companyId)->findOrFail($id);

        $rules = [
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // ✅ Switched to 'mimes'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');

            if ($file->isValid()) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $destination = storage_path('app/public/employees/messages');

                // Move the file
                $file->move($destination, $filename);
                $attachmentPath = 'employees/messages/' . $filename;

                // Log successful upload
                \Log::info('Attachment uploaded successfully', [
                    'filename' => $filename,
                    'stored_path' => $attachmentPath,
                    'full_path' => $destination . '/' . $filename,
                ]);
            } else {
                \Log::error('Attachment upload failed: file is not valid');
                return response()->json([
                    'success' => false,
                    'errors' => ['The attachment failed to upload.'],
                ], 422);
            }
        }

        // Log email queuing
        \Log::info('Queuing email to employee', [
            'to' => $employee->email,
            'subject' => $request->subject,
            'attachment' => $attachmentPath,
        ]);

        // Send the email
        Mail::to($employee->email)->queue(new EmployeeMessageMail(
            $request->subject,
            $request->body,
            $attachmentPath,
            $employee->first_name . ' ' . $employee->last_name,
            config('app.name')
        ));

        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    } catch (\Exception $e) {
        \Log::error('Error sending message: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to send message',
            'message' => $e->getMessage(),
        ], 500);
    }
}




    public function export()
    {
        try {
            $companyId = Session::get('selected_company_id');
            $employees = Employee::where('company_id', $companyId)
                                ->with(['personalInfo', 'employmentInfo'])
                                ->get();

            $filename = 'employees_export_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function () use ($employees) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Staff ID', 'First Name', 'Last Name', 'Email', 'Department', 'Position', 'Status']);
                foreach ($employees as $employee) {
                    fputcsv($file, [
                        $employee->staff_id,
                        $employee->personalInfo->first_name,
                        $employee->personalInfo->last_name,
                        $employee->email,
                        $employee->employmentInfo->department,
                        $employee->employmentInfo->position,
                        $employee->status,
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting employees: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to export employees',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

   
}
