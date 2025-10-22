<?php

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CategoriesImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $companyId;
    protected $results = [
        'imported' => 0,
        'failed' => 0,
        'errors' => []
    ];

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            try {
                $this->importCategory($row);
            } catch (\Exception $e) {
                $this->results['failed']++;
                $this->results['errors'][] = [
                    'row' => $row->toArray(),
                    'error' => $e->getMessage()
                ];
                Log::error('Category import error: ' . $e->getMessage(), [
                    'row' => $row->toArray()
                ]);
            }
        }
    }

    /**
     * @param Collection $row
     */
    protected function importCategory(Collection $row)
    {
        // Prepare data
        $data = [
            'name' => $row['category_name'] ?? $row['name'] ?? '',
            'code' => $row['category_code'] ?? $row['code'] ?? '',
            'description' => $row['description'] ?? '',
            'head_name' => $row['head_of_category_email'] ?? $row['head_name'] ?? '',
            'status' => strtolower($row['status'] ?? 'active'),
            'color' => $row['color'] ?? '#3b7ddd',
            'sub_categories' => $this->parseSubCategories($row['sub_categories_comma_separated'] ?? $row['sub_categories'] ?? ''),
            'sort_order' => (int)($row['sort_order'] ?? 0),
            'company_id' => $this->companyId,
        ];

        // Validate data
        $validator = Validator::make($data, [
            'name' => 'required|string|min:2|max:255',
            'code' => 'nullable|string|min:2|max:50',
            'description' => 'nullable|string|max:1000',
            'head_name' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'color' => 'nullable|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'sub_categories' => 'nullable|array|max:20',
            'sort_order' => 'nullable|integer|min:0|max:9999',
        ]);

        if ($validator->fails()) {
            throw new \Exception('Validation failed: ' . implode(', ', $validator->errors()->all()));
        }

        // Check for duplicates
        $existingCategory = Category::where('company_id', $this->companyId)
            ->where(function($query) use ($data) {
                $query->where('name', $data['name'])
                      ->orWhere('code', $data['code']);
            })
            ->first();

        if ($existingCategory) {
            throw new \Exception('Category with this name or code already exists');
        }

        // Create category
        Category::create($data);
        $this->results['imported']++;
    }

    /**
     * Parse sub categories from comma-separated string
     */
    protected function parseSubCategories($subCategoriesString)
    {
        if (empty($subCategoriesString)) {
            return [];
        }

        $subCategories = array_map('trim', explode(',', $subCategoriesString));
        return array_filter($subCategories, function($category) {
            return !empty($category);
        });
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.category_name' => 'required|string|min:2|max:255',
            '*.category_code' => 'nullable|string|min:2|max:50',
            '*.description' => 'nullable|string|max:1000',
            '*.head_of_category_email' => 'nullable|string|max:255',
            '*.status' => 'required|in:active,inactive',
            '*.color' => 'nullable|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            '*.sub_categories_comma_separated' => 'nullable|string',
            '*.sort_order' => 'nullable|integer|min:0|max:9999',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            '*.category_name.required' => 'Category name is required.',
            '*.category_name.min' => 'Category name must be at least 2 characters.',
            '*.category_name.max' => 'Category name cannot exceed 255 characters.',
            '*.category_code.min' => 'Category code must be at least 2 characters.',
            '*.category_code.max' => 'Category code cannot exceed 50 characters.',
            '*.description.max' => 'Description cannot exceed 1000 characters.',
            '*.head_of_category_email.max' => 'Head of category name cannot exceed 255 characters.',
            '*.status.required' => 'Status is required.',
            '*.status.in' => 'Status must be either active or inactive.',
            '*.color.regex' => 'Color must be a valid hex color code.',
            '*.sort_order.integer' => 'Sort order must be a number.',
            '*.sort_order.min' => 'Sort order cannot be negative.',
            '*.sort_order.max' => 'Sort order cannot exceed 9999.',
        ];
    }

    /**
     * Get import results
     */
    public function getResults()
    {
        return $this->results;
    }
}
