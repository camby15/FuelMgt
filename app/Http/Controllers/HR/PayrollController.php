<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HrPayroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\HrEmploymentPersonalInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\HrEmploymentEmploymentInfo;
use Carbon\Carbon;


use Illuminate\Support\Facades\Storage;



class PayrollController extends Controller
{
    // Get payroll data for datatable
    public function index(Request $request)
    {
        $companyId = Session::get('selected_company_id');
        
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        
        \Log::info('PayrollController index called', [
            'per_page' => $perPage,
            'page' => $page,
            'company_id' => $companyId,
            'request_data' => $request->all(),
            'request_method' => $request->method(),
            'content_type' => $request->header('Content-Type')
        ]);
        
        $payrolls = HrPayroll::with(['employee', 'processor'])
            ->where('company_id', $companyId)
            ->when($request->pay_period, function($query) use ($request) {
                return $query->where('pay_period', $request->pay_period);
            })
            ->when($request->status, function($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->department, function($query) use ($request) {
                return $query->whereHas('employee', function($q) use ($request) {
                    $q->where('department', $request->department);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $response = [
            'data' => $payrolls->items(),
            'pagination' => [
                'current_page' => $payrolls->currentPage(),
                'last_page' => $payrolls->lastPage(),
                'per_page' => $payrolls->perPage(),
                'total' => $payrolls->total(),
                'from' => $payrolls->firstItem(),
                'to' => $payrolls->lastItem(),
                'has_more_pages' => $payrolls->hasMorePages()
            ],
            'success' => true
        ];
        
        \Log::info('PayrollController response', [
            'items_count' => count($response['data']),
            'pagination' => $response['pagination']
        ]);

        return response()->json($response);
    }
    
    // Debug function to test per-page parameter
    public function debugPerPage(Request $request)
    {
        return response()->json([
            'per_page' => $request->input('per_page'),
            'page' => $request->input('page'),
            'all_input' => $request->all(),
            'method' => $request->method(),
            'content_type' => $request->header('Content-Type')
        ]);
    }
    
    // Get departments for Run Payroll
    public function getDepartments(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            \Log::info('Getting departments for company:', ['company_id' => $companyId]);
            
            $departments = HrEmploymentEmploymentInfo::join('employees', 'hr_employment_employment_infos.employee_id', '=', 'employees.id')
                ->where('employees.company_id', $companyId)
                ->select('department')
                ->distinct()
                ->whereNotNull('department')
                ->where('department', '!=', '')
                ->orderBy('department')
                ->get()
                ->map(function($item) {
                    return [
                        'value' => $item->department,
                        'label' => $item->department
                    ];
                });
            
            \Log::info('Departments found:', ['count' => $departments->count()]);
            
            return response()->json([
                'success' => true,
                'data' => $departments
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting departments: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load departments: ' . $e->getMessage()
            ], 500);
        }
    }
    
    
    // Run Payroll
    public function runPayroll(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            \Log::info('Run Payroll request:', [
                'company_id' => $companyId,
                'request_data' => $request->all()
            ]);
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company not selected'
                ], 400);
            }
            
            // Validate request
            $request->validate([
                'pay_period' => 'required|string|in:monthly,bi-weekly,weekly',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'employee_selection' => 'required|string|in:all,department,individual',
                'selected_employees' => 'array',
                'include_bonuses' => 'boolean',
                'include_deductions' => 'boolean'
            ]);
            
            // Get selected employees based on selection type
            $selectedEmployees = [];
            
            if ($request->employee_selection === 'all') {
                $selectedEmployees = HrEmploymentEmploymentInfo::where('company_id', $companyId)
                    ->get()
                    ->pluck('employee_id')
                    ->toArray();
            } elseif ($request->employee_selection === 'department') {
                $query = HrEmploymentEmploymentInfo::where('company_id', $companyId);
                
                if ($request->department) {
                    $query->where('department', $request->department);
                }
                
                $selectedEmployees = $query->get()->pluck('employee_id')->toArray();
            } elseif ($request->employee_selection === 'individual') {
                $selectedEmployees = $request->selected_employees ?? [];
            }
            
            \Log::info('Selected employees:', [
                'count' => count($selectedEmployees),
                'employees' => $selectedEmployees
            ]);
            
            if (empty($selectedEmployees)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No employees selected for payroll run'
                ], 400);
            }
            
            // Create payroll run record
            $payrollRun = new \App\Models\HrPayrollRun();
            $payrollRun->company_id = $companyId;
            $payrollRun->pay_period = $request->pay_period;
            $payrollRun->start_date = $request->start_date;
            $payrollRun->end_date = $request->end_date;
            $payrollRun->employee_selection = $request->employee_selection;
            $payrollRun->department = $request->department;
            $payrollRun->selected_employees = json_encode($selectedEmployees);
            $payrollRun->include_bonuses = $request->include_bonuses ?? false;
            $payrollRun->include_deductions = $request->include_deductions ?? true;
            $payrollRun->status = 'pending';
            $payrollRun->created_by = auth()->id();
            $payrollRun->save();
            
            \Log::info('Payroll run created', [
                'payroll_run_id' => $payrollRun->id,
                'company_id' => $companyId,
                'employee_count' => count($selectedEmployees),
                'pay_period' => $request->pay_period
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Payroll run created successfully',
                'data' => [
                    'payroll_run_id' => $payrollRun->id,
                    'employee_count' => count($selectedEmployees)
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error running payroll: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payroll run: ' . $e->getMessage()
            ], 500);
        }
    }

    // Store new payroll entry
    public function store(Request $request)
    {
        try {
        $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected'
                ], 400);
            }

            // Debug: Log the incoming request data
            \Log::info('Payroll store request data:', [
                'all_data' => $request->all(),
                'payment_date' => $request->payment_date,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'employee_id' => $request->employee_id
            ]);
        
        $validated = $request->validate([
            'employee_id' => 'required|exists:hr_employment_employment_infos,employee_id',
            'pay_period' => 'required|in:monthly,bi-weekly,weekly',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'payment_date' => 'nullable|string',
            'basic_salary' => 'required|numeric|min:0',
            'housing_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'overtime' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        // Parse dates - handle both YYYY-MM-DD and MM/DD/YYYY formats
        $parseDate = function($dateString) {
            if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dateString, $matches)) {
                return Carbon::createFromFormat('m/d/Y', $dateString);
            } else {
                return Carbon::parse($dateString);
            }
        };
        
        $paymentDate = $request->payment_date ? $parseDate($request->payment_date) : now();
        $startDate = $parseDate($request->start_date);
        $endDate = $request->end_date ? $parseDate($request->end_date) : null;


        // Calculate deductions (simplified for example)
        $ssnit = $request->basic_salary * 0.055; // 5.5%
        $tier2Pension = $request->basic_salary * 0.05; // 5%
        $paye = $this->calculatePaye($request->basic_salary);

        // Calculate totals
        $grossPay = $request->basic_salary 
            + $request->housing_allowance 
            + $request->transport_allowance 
            + $request->overtime 
            + $request->bonus 
            + $request->other_allowances;

        $totalDeductions = $ssnit + $tier2Pension + $paye + $request->other_deductions;
        $netPay = $grossPay - $totalDeductions;

        $payroll = HrPayroll::create([
            'company_id' => $companyId,
            'employee_id' => $request->employee_id,
            'pay_period' => $request->pay_period,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'payment_date' => $paymentDate,
            'basic_salary' => $request->basic_salary,
            'housing_allowance' => $request->housing_allowance ?? 0,
            'transport_allowance' => $request->transport_allowance ?? 0,
            'overtime' => $request->overtime ?? 0,
            'bonus' => $request->bonus ?? 0,
            'other_allowances' => $request->other_allowances ?? 0,
            'ssnit' => $ssnit,
            'paye' => $paye,
            'tier2_pension' => $tier2Pension,
            'other_deductions' => $request->other_deductions ?? 0,
            'gross_pay' => $grossPay,
            'total_deductions' => $totalDeductions,
            'net_pay' => $netPay,
            'status' => 'draft',
            'notes' => $request->notes,
            'processed_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payroll entry created successfully',
            'data' => $payroll
        ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating payroll entry',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update payroll entry
    public function update(Request $request, HrPayroll $payroll)
    {
        $companyId = Session::get('selected_company_id');
        
        // Verify the payroll belongs to the current company
        if ($payroll->company_id != $companyId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'pay_period' => 'sometimes|in:monthly,bi-weekly,weekly',
           
            'end_date' => 'nullable|date|after:start_date',
            'payment_date' => 'sometimes|date|after_or_equal:end_date',
            'basic_salary' => 'sometimes|numeric|min:0',
            'housing_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'overtime' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'status' => 'sometimes|in:draft,pending,approved,paid,rejected',
            'notes' => 'nullable|string'
        ]);

        // Recalculate if salary or allowances changed
        if ($request->has('basic_salary') || $request->has('housing_allowance') || 
            $request->has('transport_allowance') || $request->has('overtime') || 
            $request->has('bonus') || $request->has('other_allowances') || 
            $request->has('other_deductions')) {
            
            $basicSalary = $request->basic_salary ?? $payroll->basic_salary;
            $ssnit = $basicSalary * 0.055;
            $tier2Pension = $basicSalary * 0.05;
            $paye = $this->calculatePaye($basicSalary);

            $grossPay = $basicSalary 
                + ($request->housing_allowance ?? $payroll->housing_allowance)
                + ($request->transport_allowance ?? $payroll->transport_allowance)
                + ($request->overtime ?? $payroll->overtime)
                + ($request->bonus ?? $payroll->bonus)
                + ($request->other_allowances ?? $payroll->other_allowances);

            $totalDeductions = $ssnit + $tier2Pension + $paye 
                + ($request->other_deductions ?? $payroll->other_deductions);
            $netPay = $grossPay - $totalDeductions;

            $payroll->update([
                'basic_salary' => $basicSalary,
                'housing_allowance' => $request->housing_allowance ?? $payroll->housing_allowance,
                'transport_allowance' => $request->transport_allowance ?? $payroll->transport_allowance,
                'overtime' => $request->overtime ?? $payroll->overtime,
                'bonus' => $request->bonus ?? $payroll->bonus,
                'other_allowances' => $request->other_allowances ?? $payroll->other_allowances,
                'ssnit' => $ssnit,
                'paye' => $paye,
                'tier2_pension' => $tier2Pension,
                'other_deductions' => $request->other_deductions ?? $payroll->other_deductions,
                'gross_pay' => $grossPay,
                'total_deductions' => $totalDeductions,
                'net_pay' => $netPay
            ]);
        }

        // Update other fields
        $payroll->update($request->only([
            'pay_period', 'start_date', 'end_date', 'payment_date', 'status', 'notes'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Payroll updated successfully',
            'data' => $payroll
        ]);
    }

    // Process payroll (batch update)
    public function process(Request $request)
    {
        $companyId = Session::get('selected_company_id');
        
        $request->validate([
            'payroll_ids' => 'required|array',
            'payroll_ids.*' => 'exists:payrolls,id,company_id,'.$companyId,
            'payment_method' => 'required|in:bank,check,cash,mobile_money',
            'payment_date' => 'required|date',
            'notify_employees' => 'boolean'
        ]);

        $updated = HrPayroll::where('company_id', $companyId)
            ->whereIn('id', $request->payroll_ids)
            ->update([
                'status' => 'paid',
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'payment_reference' => $request->payment_reference,
                'processed_by' => Auth::id()
            ]);

        // TODO: Add notification logic if $request->notify_employees is true

        return response()->json([
            'success' => true,
            'message' => 'Payroll processed successfully',
            'data' => [
                'processed_count' => $updated
            ]
        ]);
    }

    // Calculate PAYE (simplified Ghana tax calculation)
    private function calculatePaye($basicSalary)
    {
        // Simplified Ghana PAYE calculation
        $annualSalary = $basicSalary * 12;
        
        if ($annualSalary <= 3654) { // Below threshold
            return 0;
        } elseif ($annualSalary <= 4200) {
            return ($annualSalary - 3654) * 0.05 / 12;
        } elseif ($annualSalary <= 6000) {
            return (($annualSalary - 4200) * 0.1 + 27.3) / 12;
        } elseif ($annualSalary <= 36000) {
            return (($annualSalary - 6000) * 0.175 + 207.3) / 12;
        } else {
            return (($annualSalary - 36000) * 0.25 + 5250 + 207.3) / 12;
        }
    }




public function stats(Request $request)
{
    try {
        $companyId = Session::get('selected_company_id');
        
        if (!$companyId) {
            return response()->json([
                'success' => false,
                'message' => 'No company selected'
            ], 400);
        }

        // Current month stats
        $currentMonth = now()->format('Y-m');
        $lastMonth = now()->subMonth()->format('Y-m');
        
        // Get current month payroll stats (based on created_at, not payment_date)
        $currentStats = HrPayroll::where('company_id', $companyId)
            ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$currentMonth])
            ->selectRaw('COUNT(*) as current_month_count')
            ->selectRaw('SUM(gross_pay) as total_payroll')
            ->selectRaw('AVG(gross_pay) as avg_salary')
            ->selectRaw('SUM(ssnit + paye + tier2_pension) as total_taxes')
            ->first();

        // Get last month stats for comparison (based on created_at, not payment_date)
        $lastMonthStats = HrPayroll::where('company_id', $companyId)
            ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$lastMonth])
            ->selectRaw('SUM(gross_pay) as last_month_payroll')
            ->selectRaw('AVG(gross_pay) as last_month_avg_salary')
            ->first();

        // Get total employees count
        $totalEmployees = HrEmploymentEmploymentInfo::join('employees', 'hr_employment_employment_infos.employee_id', '=', 'employees.id')
            ->where('employees.company_id', $companyId)
            ->count();

        // Get new employees this month
        $newEmployeesThisMonth = HrEmploymentEmploymentInfo::join('employees', 'hr_employment_employment_infos.employee_id', '=', 'employees.id')
            ->where('employees.company_id', $companyId)
            ->whereRaw('DATE_FORMAT(hr_employment_employment_infos.created_at, "%Y-%m") = ?', [$currentMonth])
            ->count();

        // Calculate changes
        $payrollChange = 0;
        if ($lastMonthStats && $lastMonthStats->last_month_payroll > 0) {
            $payrollChange = (($currentStats->total_payroll - $lastMonthStats->last_month_payroll) / $lastMonthStats->last_month_payroll) * 100;
        }

        $salaryGrowth = 0;
        if ($lastMonthStats && $lastMonthStats->last_month_avg_salary > 0) {
            $salaryGrowth = (($currentStats->avg_salary - $lastMonthStats->last_month_avg_salary) / $lastMonthStats->last_month_avg_salary) * 100;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_employees' => $totalEmployees,
                'new_employees_this_month' => $newEmployeesThisMonth,
                'total_payroll' => $currentStats->total_payroll ?? 0,
                'payroll_change' => round($payrollChange, 1),
                'avg_salary' => $currentStats->avg_salary ?? 0,
                'salary_growth' => round($salaryGrowth, 1),
                'total_taxes' => $currentStats->total_taxes ?? 0,
                'current_month_count' => $currentStats->current_month_count ?? 0
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error loading stats',
            'error' => $e->getMessage()
        ], 500);
    }
}

private function emptyStats()
{
    return [
        'total_employees' => 0,
        'total_payroll' => 0,
        'avg_salary' => 0,
        'net_pay_total' => 0,
        'pending_count' => 0,
    ];
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
                'employees.staff_id',
                'hr_employment_personal_infos.first_name',
                'hr_employment_personal_infos.last_name',
                'hr_employment_employment_infos.department',
                'hr_employment_employment_infos.position'
            )
            ->get()
            ->map(function ($employee) {
                return [
                    'value' => $employee->employee_id,
                    'label' => $employee->first_name . ' ' . $employee->last_name,
                    'staff_id' => $employee->staff_id,
                    'department' => $employee->department ?? 'Not Assigned',
                    'position' => $employee->position ?? 'Not Assigned',
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






    public function destroy(HrPayroll $payroll){
    $companyId = Session::get('selected_company_id');
    
    // Verify the payroll belongs to the current company
    if ($payroll->company_id != $companyId) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }

    try {
        $payroll->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Payroll entry deleted successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete payroll entry',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function show(HrPayroll $payroll)
{
    $companyId = Session::get('selected_company_id');
    
    // Verify the payroll belongs to the current company
    if ($payroll->company_id != $companyId) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }

    // Load relationships
    $payroll->load(['employee', 'processor']);
    
    return response()->json([
        'success' => true,
        'data' => $payroll
    ]);
}



// Export to Excel
public function exportExcel(Request $request)
{
    $companyId = Session::get('selected_company_id');
    $payrolls = $this->getExportData($companyId, $request->all());
    
    return (new FastExcel($payrolls))->download('payroll_export_' . date('Y-m-d') . '.xlsx');
}

// Export to PDF (using FastExcel with PDF options)
public function exportPdf(Request $request)
{
    $companyId = Session::get('selected_company_id');
    $payrolls = $this->getExportData($companyId, $request->all());
    
    return (new FastExcel($payrolls))
        ->download('payroll_export_' . date('Y-m-d') . '.pdf', function($payroll) {
            return [
                'Employee Name' => $payroll['employee_name'],
                'Employee ID' => $payroll['employee_id'],
                'Department' => $payroll['department'],
                'Basic Salary' => $payroll['basic_salary'],
                'Allowances' => $payroll['allowances'],
                'Deductions' => $payroll['deductions'],
                'Net Pay' => $payroll['net_pay'],
                'Status' => $payroll['status']
            ];
        });
}

// Export to CSV
public function exportCsv(Request $request)
{
    $companyId = Session::get('selected_company_id');
    $payrolls = $this->getExportData($companyId, $request->all());
    
    return (new FastExcel($payrolls))->download('payroll_export_' . date('Y-m-d') . '.csv');
}

// Helper method to get export data
private function getExportData($companyId, $filters = [])
{
    return HrPayroll::with(['employee'])
        ->where('company_id', $companyId)
        ->when(isset($filters['pay_period']), function($query) use ($filters) {
            return $query->where('pay_period', $filters['pay_period']);
        })
        ->when(isset($filters['status']), function($query) use ($filters) {
            return $query->where('status', $filters['status']);
        })
        ->when(isset($filters['department']), function($query) use ($filters) {
            return $query->whereHas('employee', function($q) use ($filters) {
                $q->where('department', $filters['department']);
            });
        })
        ->orderBy('payment_date', 'desc')
        ->get()
        ->map(function ($payroll) {
            return [
                'employee_name' => $payroll->employee->first_name . ' ' . $payroll->employee->last_name,
                'employee_id' => $payroll->employee->staff_id,
                'department' => $payroll->employee->department,
                'basic_salary' => number_format($payroll->basic_salary, 2),
                'allowances' => number_format(
                    $payroll->housing_allowance + 
                    $payroll->transport_allowance + 
                    $payroll->other_allowances, 
                    2
                ),
                'deductions' => number_format(
                    $payroll->ssnit + 
                    $payroll->paye + 
                    $payroll->tier2_pension + 
                    $payroll->other_deductions, 
                    2
                ),
                'net_pay' => number_format($payroll->net_pay, 2),
                'status' => ucfirst($payroll->status),
                'payment_date' => $payroll->payment_date,
                'pay_period' => ucfirst($payroll->pay_period)
            ];
        });
}

// public function import(Request $request)
// {
//     $request->validate([
//         'file' => 'required|file|mimes:xlsx,csv,txt',
//     ]);

//     $companyId = Session::get('selected_company_id');
//     $imported = [];

//     (new FastExcel)->import($request->file('file'), function ($row) use ($companyId, &$imported) {
//         try {
//             // Basic validation (add more as needed)
//             if (empty($row['employee_id']) || empty($row['pay_period']) || empty($row['payment_date']) || empty($row['basic_salary'])) {
//                 return;
//             }

//             $ssnit = $row['basic_salary'] * 0.055;
//             $tier2Pension = $row['basic_salary'] * 0.05;
//             $paye = $this->calculatePaye($row['basic_salary']);

//             $gross = $row['basic_salary'] 
//                 + ($row['housing_allowance'] ?? 0)
//                 + ($row['transport_allowance'] ?? 0)
//                 + ($row['overtime'] ?? 0)
//                 + ($row['bonus'] ?? 0)
//                 + ($row['other_allowances'] ?? 0);

//             $deductions = $ssnit + $tier2Pension + $paye + ($row['other_deductions'] ?? 0);
//             $net = $gross - $deductions;

//             $payroll = HrPayroll::create([
//                 'company_id' => $companyId,
//                 'employee_id' => $row['employee_id'],
//                 'pay_period' => $row['pay_period'],
//                 'start_date' => Carbon::now(),
//                 'end_date' => $row['end_date'] ?? null,
//                 'payment_date' => $row['payment_date'],
//                 'basic_salary' => $row['basic_salary'],
//                 'housing_allowance' => $row['housing_allowance'] ?? 0,
//                 'transport_allowance' => $row['transport_allowance'] ?? 0,
//                 'overtime' => $row['overtime'] ?? 0,
//                 'bonus' => $row['bonus'] ?? 0,
//                 'other_allowances' => $row['other_allowances'] ?? 0,
//                 'ssnit' => $ssnit,
//                 'paye' => $paye,
//                 'tier2_pension' => $tier2Pension,
//                 'other_deductions' => $row['other_deductions'] ?? 0,
//                 'gross_pay' => $gross,
//                 'total_deductions' => $deductions,
//                 'net_pay' => $net,
//                 'status' => 'draft',
//                 'notes' => $row['notes'] ?? '',
//                 'processed_by' => Auth::id(),
//             ]);

//             $imported[] = $payroll->id;
//         } catch (\Exception $e) {
//             // Optional: log errors or collect failed rows
//         }
//     });

//     return response()->json([
//         'success' => true,
//         'message' => count($imported) . ' payroll entries imported successfully.',
//     ]);
// }





public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,csv,txt',
    ]);

    $companyId = Session::get('selected_company_id');
    $imported = [];

    // Get only valid employee IDs from full joined employee info (same as getEmployees)
    $validEmployeeIds = HrEmploymentEmploymentInfo::join('employees', 'hr_employment_employment_infos.employee_id', '=', 'employees.id')
        ->join('hr_employment_personal_infos', 'hr_employment_employment_infos.employee_id', '=', 'hr_employment_personal_infos.employee_id')
        ->where('employees.company_id', $companyId)
        ->pluck('employees.id') // We only need the employee_id from the base table
        ->toArray();

    (new FastExcel)->import($request->file('file'), function ($row) use ($companyId, &$imported, $validEmployeeIds) {
        try {
            // Validate required fields
            if (
                empty($row['employee_id']) ||
                empty($row['pay_period']) ||
                empty($row['payment_date']) ||
                empty($row['basic_salary'])
            ) {
                return;
            }

            // Ensure employee exists and belongs to the selected company
            if (!in_array($row['employee_id'], $validEmployeeIds)) {
                return;
            }

            $ssnit = $row['basic_salary'] * 0.055;
            $tier2Pension = $row['basic_salary'] * 0.05;
            $paye = $this->calculatePaye($row['basic_salary']);

            $gross = $row['basic_salary']
                + ($row['housing_allowance'] ?? 0)
                + ($row['transport_allowance'] ?? 0)
                + ($row['overtime'] ?? 0)
                + ($row['bonus'] ?? 0)
                + ($row['other_allowances'] ?? 0);

            $deductions = $ssnit + $tier2Pension + $paye + ($row['other_deductions'] ?? 0);
            $net = $gross - $deductions;

            $payroll = HrPayroll::create([
                'company_id' => $companyId,
                'employee_id' => $row['employee_id'],
                'pay_period' => $row['pay_period'],
                'start_date' => Carbon::now(),
                'end_date' => $row['end_date'] ?? null,
                'payment_date' => $row['payment_date'],
                'basic_salary' => $row['basic_salary'],
                'housing_allowance' => $row['housing_allowance'] ?? 0,
                'transport_allowance' => $row['transport_allowance'] ?? 0,
                'overtime' => $row['overtime'] ?? 0,
                'bonus' => $row['bonus'] ?? 0,
                'other_allowances' => $row['other_allowances'] ?? 0,
                'ssnit' => $ssnit,
                'paye' => $paye,
                'tier2_pension' => $tier2Pension,
                'other_deductions' => $row['other_deductions'] ?? 0,
                'gross_pay' => $gross,
                'total_deductions' => $deductions,
                'net_pay' => $net,
                'status' => 'draft',
                'notes' => $row['notes'] ?? '',
                'processed_by' => Auth::id(),
            ]);

            $imported[] = $payroll->id;
        } catch (\Exception $e) {
            // Optionally log $e->getMessage() for debugging
        }
    });

    return response()->json([
        'success' => true,
        'message' => count($imported) . ' payroll entries imported successfully.',
    ]);
}



}