<?php

namespace App\Http\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

class UpdateDepartmentCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization will be handled in the controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $companyId = Session::get('selected_company_id');
        $departmentId = $this->route('department') ?? $this->route('id');

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('department_categories')
                    ->where('company_id', $companyId)
                    ->ignore($departmentId)
                    ->whereNull('deleted_at')
            ],
            'code' => [
                'sometimes',
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[A-Z0-9\-_]+$/',
                Rule::unique('department_categories')
                    ->where('company_id', $companyId)
                    ->ignore($departmentId)
                    ->whereNull('deleted_at')
            ],
            'description' => [
                'sometimes',
                'nullable',
                'string',
                'max:1000'
            ],
            'head_name' => [
                'sometimes',
                'nullable',
                'string',
                'max:255'
            ],
            'status' => [
                'sometimes',
                'required',
                'string',
                'in:active,inactive'
            ],
            'color' => [
                'sometimes',
                'nullable',
                'string',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'
            ],
            'sub_departments' => [
                'sometimes',
                'nullable',
                'array',
                'max:20' // Maximum 20 sub departments
            ],
            'sub_departments.*' => [
                'required_with:sub_departments',
                'string',
                'min:2',
                'max:255',
                'distinct' // Ensure no duplicate sub department names
            ],
            'sort_order' => [
                'sometimes',
                'nullable',
                'integer',
                'min:0',
                'max:9999'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Department name is required.',
            'name.min' => 'Department name must be at least 2 characters.',
            'name.max' => 'Department name cannot exceed 255 characters.',
            'name.unique' => 'A department with this name already exists in your company.',
            
            'code.required' => 'Department code is required.',
            'code.min' => 'Department code must be at least 2 characters.',
            'code.max' => 'Department code cannot exceed 50 characters.',
            'code.regex' => 'Department code can only contain uppercase letters, numbers, hyphens, and underscores.',
            'code.unique' => 'A department with this code already exists in your company.',
            
            'description.max' => 'Description cannot exceed 1000 characters.',
            
            'head_name.max' => 'Department head name cannot exceed 255 characters.',
            
            'status.required' => 'Department status is required.',
            'status.in' => 'Department status must be either active or inactive.',
            
            'color.regex' => 'Color must be a valid hex color code (e.g., #FF0000).',
            
            'sub_departments.array' => 'Sub departments must be provided as a list.',
            'sub_departments.max' => 'You can only add up to 20 sub departments.',
            'sub_departments.*.required_with' => 'Sub department name is required.',
            'sub_departments.*.min' => 'Sub department name must be at least 2 characters.',
            'sub_departments.*.max' => 'Sub department name cannot exceed 255 characters.',
            'sub_departments.*.distinct' => 'Sub department names must be unique.',
            
            'sort_order.integer' => 'Sort order must be a number.',
            'sort_order.min' => 'Sort order cannot be negative.',
            'sort_order.max' => 'Sort order cannot exceed 9999.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'department name',
            'code' => 'department code',
            'description' => 'description',
            'head_name' => 'department head',
            'status' => 'status',
            'color' => 'color',
            'sub_departments' => 'sub departments',
            'sub_departments.*' => 'sub department',
            'sort_order' => 'sort order'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert code to uppercase if provided
        if ($this->has('code') && !empty($this->input('code'))) {
            $this->merge([
                'code' => strtoupper($this->input('code'))
            ]);
        }

        // Set default color if empty
        if ($this->has('color') && empty($this->input('color'))) {
            $this->merge([
                'color' => '#3b7ddd'
            ]);
        }

        // Clean up sub departments array
        if ($this->has('sub_departments') && is_array($this->input('sub_departments'))) {
            $subDepartments = array_filter($this->input('sub_departments'), function ($value) {
                return !empty(trim($value));
            });
            
            $this->merge([
                'sub_departments' => array_values($subDepartments)
            ]);
        }

        // Set default sort order if empty
        if ($this->has('sort_order') && empty($this->input('sort_order'))) {
            $this->merge([
                'sort_order' => 0
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $companyId = Session::get('selected_company_id');
            $departmentId = $this->route('department') ?? $this->route('id');
            
            // Check if the department belongs to the current company
            if ($departmentId) {
                $department = \App\Models\DepartmentCategory::where('id', $departmentId)
                    ->where('company_id', $companyId)
                    ->first();
                
                if (!$department) {
                    $validator->errors()->add('department', 'The department does not exist or does not belong to your company.');
                }
            }
        });
    }
}