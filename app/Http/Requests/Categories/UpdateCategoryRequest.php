<?php

namespace App\Http\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

class UpdateCategoryRequest extends FormRequest
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
        $categoryId = $this->route('category') ?? $this->route('id');

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('categories')
                    ->where('company_id', $companyId)
                    ->ignore($categoryId)
                    ->whereNull('deleted_at')
            ],
            'code' => [
                'sometimes',
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[A-Z0-9\-_]+$/',
                Rule::unique('categories')
                    ->where('company_id', $companyId)
                    ->ignore($categoryId)
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
            'sub_categories' => [
                'sometimes',
                'nullable',
                'array',
                'max:20' // Maximum 20 sub categories
            ],
            'sub_categories.*' => [
                'required_with:sub_categories',
                'string',
                'min:2',
                'max:255',
                'distinct' // Ensure no duplicate sub category names
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
            'name.required' => 'Category name is required.',
            'name.min' => 'Category name must be at least 2 characters.',
            'name.max' => 'Category name cannot exceed 255 characters.',
            'name.unique' => 'A category with this name already exists in your company.',
            
            'code.required' => 'Category code is required.',
            'code.min' => 'Category code must be at least 2 characters.',
            'code.max' => 'Category code cannot exceed 50 characters.',
            'code.regex' => 'Category code can only contain uppercase letters, numbers, hyphens, and underscores.',
            'code.unique' => 'A category with this code already exists in your company.',
            
            'description.max' => 'Description cannot exceed 1000 characters.',
            
            'head_name.max' => 'Category head name cannot exceed 255 characters.',
            
            'status.required' => 'Category status is required.',
            'status.in' => 'Category status must be either active or inactive.',
            
            'color.regex' => 'Color must be a valid hex color code (e.g., #FF0000).',
            
            'sub_categories.array' => 'Sub categories must be provided as a list.',
            'sub_categories.max' => 'You can only add up to 20 sub categories.',
            'sub_categories.*.required_with' => 'Sub category name is required.',
            'sub_categories.*.min' => 'Sub category name must be at least 2 characters.',
            'sub_categories.*.max' => 'Sub category name cannot exceed 255 characters.',
            'sub_categories.*.distinct' => 'Sub category names must be unique.',
            
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
            'name' => 'category name',
            'code' => 'category code',
            'description' => 'description',
            'head_name' => 'category head',
            'status' => 'status',
            'color' => 'color',
            'sub_categories' => 'sub categories',
            'sub_categories.*' => 'sub category',
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

        // Clean up sub categories array
        if ($this->has('sub_categories') && is_array($this->input('sub_categories'))) {
            $subCategories = array_filter($this->input('sub_categories'), function ($value) {
                return !empty(trim($value));
            });
            
            $this->merge([
                'sub_categories' => array_values($subCategories)
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
            $categoryId = $this->route('category') ?? $this->route('id');
            
            // Check if the category belongs to the current company
            if ($categoryId) {
                $category = \App\Models\Category::where('id', $categoryId)
                    ->where('company_id', $companyId)
                    ->first();
                
                if (!$category) {
                    $validator->errors()->add('category', 'The category does not exist or does not belong to your company.');
                }
            }
        });
    }
}
