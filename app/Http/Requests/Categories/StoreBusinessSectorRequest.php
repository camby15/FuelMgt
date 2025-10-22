<?php

namespace App\Http\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusinessSectorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:business_sectors,name,NULL,id,company_id,' . session('selected_company_id'),
            'description' => 'nullable|string|max:1000',
            'head_name' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'sub_sectors' => 'nullable|array',
            'sub_sectors.*' => 'string|max:255',
            'sort_order' => 'nullable|integer|min:0'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Business sector name is required.',
            'name.unique' => 'A business sector with this name already exists.',
            'name.max' => 'Business sector name must not exceed 255 characters.',
            'description.max' => 'Description must not exceed 1000 characters.',
            'head_name.max' => 'Head name must not exceed 255 characters.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either active or inactive.',
            'sub_sectors.array' => 'Sub-sectors must be an array.',
            'sub_sectors.*.string' => 'Each sub-sector must be a string.',
            'sub_sectors.*.max' => 'Each sub-sector must not exceed 255 characters.',
            'sort_order.integer' => 'Sort order must be an integer.',
            'sort_order.min' => 'Sort order must be at least 0.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'business sector name',
            'description' => 'description',
            'head_name' => 'head name',
            'status' => 'status',
            'sub_sectors' => 'sub-sectors',
            'sort_order' => 'sort order'
        ];
    }
}