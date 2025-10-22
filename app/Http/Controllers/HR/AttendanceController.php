<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\EmploymentPersonalInfo;
use App\Models\HrEmploymentEmploymentInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\HrEmploymentPersonalInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;




class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $date = $request->input('date', date('Y-m-d'));

            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $attendances = Attendance::with(['personalInfo', 'employmentInfo'])
                ->where('company_id', $companyId)
                ->whereDate('date', $date)
                ->whereNull('deleted_at')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $attendances,
                'message' => 'Attendance records retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch attendance records',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getStats(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $date = $request->input('date', date('Y-m-d'));

            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $stats = Attendance::where('company_id', $companyId)
                ->whereDate('date', $date)
                ->whereNull('deleted_at')
                ->selectRaw("
                    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present,
                    SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent,
                    SUM(CASE WHEN status = 'on_leave' THEN 1 ELSE 0 END) as on_leave,
                    COUNT(*) as total
                ")
                ->first();

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Attendance stats retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch attendance stats',
                'message' => $e->getMessage()
            ], 500);
        }
    }

 public function store(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
    }

    $companyId = Session::get('selected_company_id');
    if (!$companyId) {
        return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
    }

    $validator = Validator::make($request->all(), [
        'employee_id' => 'required|exists:hr_employment_personal_infos,employee_id',
        'date' => 'required|date',
        'clock_in' => 'nullable|date_format:H:i',
        'clock_out' => 'nullable|date_format:H:i|after:clock_in',
        'status' => 'required|in:present,late,absent,on_leave,half_day,holiday',
        'notes' => 'nullable|string'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        // Prevent duplicate for the same date
        $exists = Attendance::where('employee_id', $request->employee_id)
            ->where('company_id', $companyId)
            ->where('date', $request->date)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance already recorded for this employee on the selected date.'
            ], 409);
        }

        $attendance = Attendance::create([
            'company_id' => $companyId,
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'status' => $request->status,
            'notes' => $request->notes,
            'marked_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'data' => $attendance,
            'message' => 'Attendance recorded successfully'
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Failed to record attendance',
            'message' => $e->getMessage()
        ], 500);
    }
}


 public function bulkUpdate(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
    }

    $companyId = Session::get('selected_company_id');
    if (!$companyId) {
        return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
    }

    $rawEmployeeIds = $request->employee_ids ?? [];
    $employeeIds = [];

    // Interpret employee_ids input
    if (is_array($rawEmployeeIds) && count($rawEmployeeIds) === 1) {
        $firstValue = $rawEmployeeIds[0];

        if ($firstValue === 'all') {
            // All employees
            $employeeIds = DB::table('hr_employment_personal_infos')
                ->join('employees', 'hr_employment_personal_infos.employee_id', '=', 'employees.id')
                ->where('employees.company_id', $companyId)
                ->pluck('hr_employment_personal_infos.employee_id')
                ->toArray();

        } elseif (str_starts_with($firstValue, 'department:')) {
            // By department
            $department = explode(':', $firstValue)[1];

            $employeeIds = DB::table('hr_employment_personal_infos')
                ->join('employees', 'hr_employment_personal_infos.employee_id', '=', 'employees.id')
                ->where('employees.company_id', $companyId)
                ->where('hr_employment_personal_infos.department', $department)
                ->pluck('hr_employment_personal_infos.employee_id')
                ->toArray();
        }
    } elseif (is_array($rawEmployeeIds)) {
        $employeeIds = $rawEmployeeIds;
    }

    if (empty($employeeIds)) {
        return response()->json([
            'success' => false,
            'message' => 'No employees selected or found.'
        ], 422);
    }

    // Validate other fields
    $validator = Validator::make($request->all(), [
        'date' => 'required|date|before_or_equal:today',
        'status' => 'required|in:present,late,absent,on_leave,half_day,holiday',
        'notes' => 'nullable|string'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $now = now();
        $attendanceRecords = [];

        // Get already marked attendances for the same day
        $existingIds = Attendance::where('company_id', $companyId)
            ->whereIn('employee_id', $employeeIds)
            ->where('date', $request->date)
            ->pluck('employee_id')
            ->toArray();

        // Remove employees who already have attendance
        $newEmployeeIds = array_diff($employeeIds, $existingIds);

        if (empty($newEmployeeIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance already recorded for all selected employees on this date.'
            ], 409);
        }

        foreach ($newEmployeeIds as $employeeId) {
            $attendanceRecords[] = [
                'company_id' => $companyId,
                'employee_id' => $employeeId,
                'date' => $request->date,
                'status' => $request->status,
                'notes' => $request->notes,
                'marked_by' => Auth::id(),
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        Attendance::insert($attendanceRecords);

        return response()->json([
            'success' => true,
            'message' => 'Bulk attendance updated successfully for ' . count($newEmployeeIds) . ' employees.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Bulk update failed.',
            'message' => $e->getMessage()
        ], 500);
    }
}




    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        $companyId = Session::get('selected_company_id');
        if (!$companyId) {
            return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
        }

        $validator = Validator::make($request->all(), [
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after:clock_in',
            'status' => 'required|in:present,late,absent,on_leave,half_day,holiday',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $attendance = Attendance::where('company_id', $companyId)
                ->whereNull('deleted_at')
                ->findOrFail($id);

            $attendance->update([
                'clock_in' => $request->clock_in,
                'clock_out' => $request->clock_out,
                'status' => $request->status,
                'notes' => $request->notes,
                'marked_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'data' => $attendance,
                'message' => 'Attendance updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update attendance',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function history(Request $request, $employeeId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', now()->format('Y-m-d'));
            $status = $request->input('status');

            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $query = Attendance::where('company_id', $companyId)
                ->where('employee_id', $employeeId)
                ->whereBetween('date', [$startDate, $endDate])
                ->whereNull('deleted_at')
                ->orderBy('date', 'desc');

            if ($status) {
                $query->where('status', $status);
            }

            $history = $query->get();

            // Calculate summary stats
            $stats = [
                'present' => $history->where('status', 'present')->count(),
                'late' => $history->where('status', 'late')->count(),
                'absent' => $history->where('status', 'absent')->count(),
                'on_leave' => $history->where('status', 'on_leave')->count(),
                'total' => $history->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $history,
                'stats' => $stats,
                'message' => 'Attendance history retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch attendance history',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getEmployees(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');

            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

$employees = HrEmploymentEmploymentInfo::join('employees', 'hr_employment_employment_infos.employee_id', '=', 'employees.id')
    ->join('hr_employment_personal_infos', 'hr_employment_employment_infos.employee_id', '=', 'hr_employment_personal_infos.employee_id')
    ->where('employees.company_id', $companyId)
    ->select(
        'hr_employment_employment_infos.employee_id',
        'hr_employment_personal_infos.first_name',
        'hr_employment_personal_infos.last_name'
    )
    ->get()
    ->map(function ($employee) {
        return [
            'value' => $employee->employee_id,
            'label' => $employee->first_name . ' ' . $employee->last_name
        ];
    });





            return response()->json([
                'success' => true,
                'data' => $employees,
                'message' => 'Employees retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch employees',
                'message' => $e->getMessage()
            ], 500);
        }
    }



// ... (keep all your existing methods until the import method)

// public function import(Request $request)
// {
//     if (!Auth::check()) {
//         return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
//     }

//     $companyId = Session::get('selected_company_id');
//     if (!$companyId) {
//         return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
//     }

//     $validator = Validator::make($request->all(), [
//         'importFile' => 'required|file|mimes:xlsx,xls,csv|max:5120', // 5MB max
//         'overwriteExisting' => 'nullable|boolean'
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'success' => false,
//             'errors' => $validator->errors()
//         ], 422);
//     }

//     try {
//         $file = $request->file('importFile');
//         $overwrite = $request->boolean('overwriteExisting', false);
        
//         // Process file with FastExcel
//         $data = (new FastExcel)->import($file->getPathname());
        
//         // Normalize array keys to snake_case
//         $data = collect($data)->map(function ($item) {
//             return array_change_key_case($item, CASE_LOWER);
//         })->toArray();

//         // Validate imported data structure
//         $validatedData = $this->validateImportData($data, $companyId);
//         if (!$validatedData['success']) {
//             return response()->json($validatedData, 422);
//         }

//         // Process import
//         $importResult = $this->processImport($validatedData['data'], $companyId, $overwrite);

//         return response()->json([
//             'success' => true,
//             'data' => $importResult,
//             'message' => 'Attendance imported successfully. ' . 
//                         $importResult['created'] . ' created, ' . 
//                         $importResult['updated'] . ' updated, ' . 
//                         $importResult['skipped'] . ' skipped.'
//         ]);

//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'error' => 'Failed to import attendance',
//             'message' => $e->getMessage()
//         ], 500);
//     }
// }





public function import(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
    }

    $companyId = Session::get('selected_company_id');
    if (!$companyId) {
        return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
    }

    $validator = Validator::make($request->all(), [
        'importFile' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        'overwriteExisting' => 'nullable|boolean'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $file = $request->file('importFile');
        $overwrite = $request->boolean('overwriteExisting', false);

        $data = (new FastExcel)->import($file->getPathname());

        $data = collect($data)->map(function ($row) {
            $normalized = [];
            foreach ($row as $key => $value) {
                $normalized[Str::snake(trim($key))] = $value;
            }
            return $normalized;
        })->toArray();

        $validatedData = $this->validateImportData($data, $companyId);
        if (!$validatedData['success']) {
            return response()->json($validatedData, 422);
        }

        $importResult = $this->processImport($validatedData['data'], $companyId, $overwrite);

        return response()->json([
            'success' => true,
            'data' => $importResult,
            'message' => 'Attendance imported successfully. ' .
                         $importResult['created'] . ' created, ' .
                         $importResult['updated'] . ' updated, ' .
                         $importResult['skipped'] . ' skipped.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Failed to import attendance',
            'message' => $e->getMessage()
        ], 500);
    }
}



// You can remove the processExcelFile() and processCsvFile() methods since FastExcel handles both

/**
 * Validate imported data structure
 */
private function validateImportData($data, $companyId)
{
    $validated = [];
    $errors = [];
    
    foreach ($data as $index => $row) {
        // Ensure keys are in snake_case
        // $row = array_change_key_case($row, CASE_SNAKE);
        //    $row = array_change_key_case($row, CASE_LOWER);
        
        $validator = Validator::make($row, [
               'employee_id' => 'required|exists:hr_employment_personal_infos,employee_id',

            'date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after:clock_in',
            'status' => 'required|in:present,late,absent,on_leave,half_day,holiday',
            'notes' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            $errors[] = [
                'row' => $index + 2, // +1 for header, +1 for 0-based index
                'errors' => $validator->errors()->toArray(),
                'data' => $row
            ];
            continue;
        }
        
        $validated[] = $validator->validated();
    }
    
    if (!empty($errors)) {
        return [
            'success' => false,
            'errors' => $errors,
            'message' => 'Validation failed for some rows'
        ];
    }
    
    return ['success' => true, 'data' => $validated];
}

// Keep your existing processImport() method

/**
 * Process Excel file using PhpSpreadsheet
 */
private function processExcelFile($file)
{
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();
    
    $headers = array_shift($rows);
    $data = [];
    
    foreach ($rows as $row) {
        if (!array_filter($row)) continue; // Skip empty rows
        
        $data[] = array_combine($headers, $row);
    }
    
    return $data;
}

/**
 * Process CSV file
 */
private function processCsvFile($file)
{
    $data = [];
    $handle = fopen($file->getPathname(), 'r');
    $headers = fgetcsv($handle);
    
    while (($row = fgetcsv($handle)) !== false) {
        if (!array_filter($row)) continue; // Skip empty rows
        
        $data[] = array_combine($headers, $row);
    }
    
    fclose($handle);
    return $data;
}



/**
 * Process the actual import
 */
private function processImport($data, $companyId, $overwrite)
{
    $created = 0;
    $updated = 0;
    $skipped = 0;
    $currentUserId = Auth::id();
    $now = now();
    
    foreach ($data as $record) {
        $existing = Attendance::where('company_id', $companyId)
            ->where('employee_id', $record['employee_id'])
            ->whereDate('date', $record['date'])
            ->first();
            
        if ($existing) {
            if ($overwrite) {
                $existing->update([
                    'clock_in' => $record['clock_in'],
                    'clock_out' => $record['clock_out'],
                    'status' => $record['status'],
                    'notes' => $record['notes'] ?? null,
                    'marked_by' => $currentUserId,
                    'updated_at' => $now
                ]);
                $updated++;
            } else {
                $skipped++;
            }
        } else {
            Attendance::create([
                'company_id' => $companyId,
                'employee_id' => $record['employee_id'],
                'date' => $record['date'],
                'clock_in' => $record['clock_in'],
                'clock_out' => $record['clock_out'],
                'status' => $record['status'],
                'notes' => $record['notes'] ?? null,
                'marked_by' => $currentUserId,
                'created_at' => $now,
                'updated_at' => $now
            ]);
            $created++;
        }
    }
    
    return [
        'created' => $created,
        'updated' => $updated,
        'skipped' => $skipped,
        'total' => count($data)
    ];
}

    /**
     * Get leaves for the calendar view
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeaves(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $startDate = $request->input('start');
            $endDate = $request->input('end');

            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            // Get leaves from attendance table where status is on_leave
            $leaves = Attendance::with(['personalInfo'])
                ->where('company_id', $companyId)
                ->where('status', 'on_leave')
                ->whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $endDate)
                ->whereNull('deleted_at')
                ->get()
                ->map(function ($attendance) {
                    return [
                        'id' => $attendance->id,
                        'employee_name' => $attendance->personalInfo ? 
                            $attendance->personalInfo->first_name . ' ' . $attendance->personalInfo->last_name : 
                            'Unknown Employee',
                        'type' => 'Leave',
                        'start_date' => $attendance->date,
                        'end_date' => $attendance->date, // Single day leave
                        'color' => '#4e73df', // Blue color for leaves
                        'status' => $attendance->status,
                        'notes' => $attendance->notes
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $leaves,
                'message' => 'Leaves retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch leaves',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}