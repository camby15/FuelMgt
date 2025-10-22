<?php

namespace App\Imports;

use App\Models\DepartmentCategory;
use App\Models\CompanySubUser;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DepartmentCategoriesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    protected $companyId;
    protected $importedCount = 0;
    protected $failedCount = 0;
    protected $errors = [];

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * Transform each row into a model.
     */
    public function model(array $row)
    {
        try {
            // Debug: Log the raw row data
            Log::info('Import row debug', [
                'raw_row' => $row,
                'available_keys' => array_keys($row)
            ]);
            
            // Clean and validate the row data
            $name = trim($row['department_name'] ?? '');
            $code = trim(strtoupper($row['department_code'] ?? ''));
            $description = trim($row['description'] ?? '');
            $headEmail = trim($row['head_of_department_email'] ?? '');
            $status = strtolower(trim($row['status'] ?? 'active'));
            $color = trim($row['color'] ?? '#3b7ddd');
            $subDepartments = trim($row['sub_departments_comma_separated'] ?? '');
            $sortOrder = intval($row['sort_order'] ?? 0);
            
            // Debug: Log extracted values
            Log::info('Import values debug', [
                'name' => $name,
                'head_email_raw' => $headEmail,
                'head_email_key_exists' => isset($row['head_of_department_email'])
            ]);

            // Skip empty rows
            if (empty($name)) {
                return null;
            }

            // Validate status
            if (!in_array($status, ['active', 'inactive'])) {
                $status = 'active';
            }

            // Validate color format
            if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
                $color = '#3b7ddd';
            }

            // Generate unique code if not provided or already exists
            if (empty($code) || $this->codeExists($code)) {
                $code = $this->generateUniqueCode($name);
            }

            // Find head of department by email and get the name
            $headName = null;
            if (!empty($headEmail)) {
                Log::info('Processing head of department', [
                    'head_email_input' => $headEmail,
                    'is_email' => filter_var($headEmail, FILTER_VALIDATE_EMAIL)
                ]);
                
                // If it looks like an email, try to find user by email
                if (filter_var($headEmail, FILTER_VALIDATE_EMAIL)) {
                    $head = CompanySubUser::where('company_id', $this->companyId)
                        ->where('email', $headEmail)
                        ->where('status', true) // Changed from 'active' to true for boolean field
                        ->first();
                    
                    Log::info('Email lookup result', [
                        'found_user' => $head ? true : false,
                        'user_name' => $head ? $head->fullname : null
                    ]);
                    
                    if ($head) {
                        $headName = $head->fullname; // Get the actual name instead of ID
                    } else {
                        // If email not found, use the email as the name for now
                        $headName = $headEmail;
                        Log::info('Email not found, using email as name', ['head_name' => $headName]);
                    }
                } else {
                    // If it's not an email, treat it as a direct name
                    $headName = trim($headEmail);
                    Log::info('Using direct name', ['head_name' => $headName]);
                }
            }
            
            Log::info('Final head name', ['head_name' => $headName]);

            // Process sub departments
            $subDepartmentsArray = [];
            if (!empty($subDepartments)) {
                $subDepartmentsArray = array_map('trim', explode(',', $subDepartments));
                $subDepartmentsArray = array_filter($subDepartmentsArray, function($value) {
                    return !empty($value);
                });
            }

            // Check if department with this name already exists
            $existingDepartment = DepartmentCategory::where('company_id', $this->companyId)
                ->where('name', $name)
                ->whereNull('deleted_at')
                ->first();

            if ($existingDepartment) {
                // Update existing department
                $existingDepartment->update([
                    'description' => $description,
                    'head_name' => $headName,
                    'status' => $status,
                    'color' => $color,
                    'sub_departments' => $subDepartmentsArray,
                    'sort_order' => $sortOrder,
                    'updated_by' => auth('company_sub_user')->id()
                ]);

                $this->importedCount++;
                return null; // Don't create new model, we updated existing
            }

            // Create new department
            $department = new DepartmentCategory([
                'name' => $name,
                'code' => $code,
                'description' => $description,
                'company_id' => $this->companyId,
                'head_name' => $headName,
                'status' => $status,
                'color' => $color,
                'sub_departments' => $subDepartmentsArray,
                'sort_order' => $sortOrder,
                'created_by' => auth('company_sub_user')->id()
            ]);

            $this->importedCount++;

            Log::info('Department imported successfully', [
                'name' => $name,
                'code' => $code,
                'company_id' => $this->companyId
            ]);

            return $department;

        } catch (\Exception $e) {
            $this->failedCount++;
            $this->errors[] = "Row error: " . $e->getMessage();
            
            Log::error('Error importing department row', [
                'row' => $row,
                'error' => $e->getMessage(),
                'company_id' => $this->companyId
            ]);
            
            return null;
        }
    }

    /**
     * Define validation rules for each row.
     */
    public function rules(): array
    {
        return [
            'department_name' => 'required|string|max:255',
            'department_code' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'head_of_department_email' => 'nullable|email',
            'status' => 'nullable|in:active,inactive',
            'color' => 'nullable|string',
            'sub_departments_comma_separated' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0'
        ];
    }

    /**
     * Custom error messages for validation.
     */
    public function customValidationMessages()
    {
        return [
            'department_name.required' => 'Department name is required.',
            'department_name.max' => 'Department name cannot exceed 255 characters.',
            'department_code.max' => 'Department code cannot exceed 50 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'head_of_department_email.email' => 'Head of department email must be a valid email address.',
            'status.in' => 'Status must be either active or inactive.',
            'sort_order.integer' => 'Sort order must be a number.',
            'sort_order.min' => 'Sort order cannot be negative.'
        ];
    }

    /**
     * Check if a code already exists for this company.
     */
    private function codeExists(string $code): bool
    {
        return DepartmentCategory::where('company_id', $this->companyId)
            ->where('code', $code)
            ->whereNull('deleted_at')
            ->exists();
    }

    /**
     * Generate a unique code for the department.
     */
    private function generateUniqueCode(string $name): string
    {
        // Create code from name
        $baseCode = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $name));
        $baseCode = substr($baseCode, 0, 4);
        
        if (strlen($baseCode) < 2) {
            $baseCode = 'DEPT';
        }
        
        $counter = 1;
        $code = $baseCode;
        
        while ($this->codeExists($code)) {
            $code = $baseCode . str_pad($counter, 2, '0', STR_PAD_LEFT);
            $counter++;
            
            if ($counter > 99) {
                $code = $baseCode . '-' . time();
                break;
            }
        }
        
        return $code;
    }

    /**
     * Get import results.
     */
    public function getResults(): array
    {
        return [
            'imported' => $this->importedCount,
            'failed' => $this->failedCount + count($this->failures()) + count($this->errors()),
            'errors' => array_merge($this->errors, $this->getErrorMessages()),
            'failures' => $this->failures()
        ];
    }

    /**
     * Get error messages from failures.
     */
    private function getErrorMessages(): array
    {
        $messages = [];
        
        foreach ($this->failures() as $failure) {
            $messages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
        }
        
        foreach ($this->errors() as $error) {
            $messages[] = "Import error: " . $error->getMessage();
        }
        
        return $messages;
    }
}