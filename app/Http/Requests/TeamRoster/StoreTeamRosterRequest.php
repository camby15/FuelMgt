<?php

namespace App\Http\Requests\TeamRoster;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StoreTeamRosterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Session::has('selected_company_id');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'team_id' => 'required|exists:team_paring,id',
            'roster_name' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'roster_period' => 'required|in:weekly,monthly',
            'working_days' => 'nullable|array',
            'working_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'leave_days' => 'nullable|array',
            'leave_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'work_start_time' => 'nullable|date_format:H:i',
            'work_end_time' => 'nullable|date_format:H:i|after:work_start_time',
            'leave_type' => 'nullable|in:vacation,sick,personal,holiday,training',
            'leave_reason' => 'nullable|string|max:255',
            'roster_status' => 'required|in:draft,active,inactive',
            'max_working_hours' => 'nullable|integer|min:1|max:168',
            'roster_notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'team_id.required' => 'Please select a team.',
            'team_id.exists' => 'The selected team does not exist.',
            'roster_name.required' => 'Roster name is required.',
            'roster_name.max' => 'Roster name cannot exceed 255 characters.',
            'start_date.required' => 'Start date is required.',
            'start_date.after_or_equal' => 'Start date must be today or later.',
            'end_date.required' => 'End date is required.',
            'end_date.after' => 'End date must be after start date.',
            'roster_period.required' => 'Roster period is required.',
            'roster_period.in' => 'Roster period must be either weekly or monthly.',
            'working_days.array' => 'Working days must be an array.',
            'working_days.*.in' => 'Invalid working day selected.',
            'leave_days.array' => 'Leave days must be an array.',
            'leave_days.*.in' => 'Invalid leave day selected.',
            'work_start_time.date_format' => 'Work start time must be in HH:MM format.',
            'work_end_time.date_format' => 'Work end time must be in HH:MM format.',
            'work_end_time.after' => 'Work end time must be after work start time.',
            'leave_type.in' => 'Invalid leave type selected.',
            'leave_reason.max' => 'Leave reason cannot exceed 255 characters.',
            'roster_status.required' => 'Roster status is required.',
            'roster_status.in' => 'Invalid roster status selected.',
            'max_working_hours.integer' => 'Max working hours must be a number.',
            'max_working_hours.min' => 'Max working hours must be at least 1.',
            'max_working_hours.max' => 'Max working hours cannot exceed 168.',
            'roster_notes.max' => 'Roster notes cannot exceed 1000 characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure working_days and leave_days are arrays
        if ($this->has('working_days') && !is_array($this->working_days)) {
            $this->merge(['working_days' => []]);
        }
        
        if ($this->has('leave_days') && !is_array($this->leave_days)) {
            $this->merge(['leave_days' => []]);
        }

        // Set default values
        $this->merge([
            'max_working_hours' => $this->max_working_hours ?? 40,
            'roster_status' => $this->roster_status ?? 'draft',
        ]);
    }

    /**
     * Get the validated data with additional fields.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        // Add company_id and user tracking
        $validated['company_id'] = Session::get('selected_company_id');
        $validated['created_by'] = $this->getAuthenticatedUserId();
        $validated['updated_by'] = $this->getAuthenticatedUserId();
        
        return $validated;
    }

    /**
     * Get the authenticated user ID from the appropriate guard.
     */
    private function getAuthenticatedUserId(): ?int
    {
        // Check company_sub_user guard first
        if (Auth::guard('company_sub_user')->check()) {
            return Auth::guard('company_sub_user')->id();
        }
        
        // Check sub_user guard
        if (Auth::guard('sub_user')->check()) {
            return Auth::guard('sub_user')->id();
        }
        
        // Check default web guard
        if (Auth::check()) {
            return Auth::id();
        }
        
        return null;
    }
}