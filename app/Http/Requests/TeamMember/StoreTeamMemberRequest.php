<?php

namespace App\Http\Requests\TeamMember;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamMemberRequest extends FormRequest
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
        $companyId = session('selected_company_id');
        
        return [
            'full_name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:255|unique:team_members,employee_id,NULL,id,company_id,' . $companyId,
            'position' => 'required|string|max:255',
            'department_id' => 'required|exists:department_categories,id',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:team_members,email,NULL,id,company_id,' . $companyId,
            'hire_date' => 'nullable|date|before_or_equal:today',
            'status' => 'required|in:active,inactive,on-leave',
            'notes' => 'nullable|string|max:1000',
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
            'full_name.required' => 'The full name field is required.',
            'employee_id.required' => 'The employee ID field is required.',
            'employee_id.unique' => 'This employee ID is already taken.',
            'position.required' => 'The position field is required.',
            'department_id.required' => 'Please select a department.',
            'department_id.exists' => 'Please select a valid department.',
            'phone.required' => 'The phone number field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already taken.',
            'hire_date.date' => 'Please enter a valid hire date.',
            'hire_date.before_or_equal' => 'The hire date cannot be in the future.',
            'status.required' => 'Please select a status.',
            'status.in' => 'Please select a valid status.',
            'notes.max' => 'The notes field cannot exceed 1000 characters.',
        ];
    }
}
