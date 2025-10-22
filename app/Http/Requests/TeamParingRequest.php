<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamParingRequest extends FormRequest
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
        $teamId = $this->route('team_paring');
        
        // Build unique rules for team_name
        $teamNameUniqueRule = Rule::unique('team_paring', 'team_name')
            ->where('company_id', session('selected_company_id'))
            ->whereNull('deleted_at');
        
        // Build unique rules for team_code  
        $teamCodeUniqueRule = Rule::unique('team_paring', 'team_code')
            ->whereNull('deleted_at');
            
        // Only ignore the current team ID if we're updating (not creating)
        if ($teamId) {
            $teamNameUniqueRule = $teamNameUniqueRule->ignore($teamId);
            $teamCodeUniqueRule = $teamCodeUniqueRule->ignore($teamId);
        }
        
        return [
            // Basic team information
            'team_name' => [
                'required',
                'string',
                'max:255',
                $teamNameUniqueRule
            ],
            'team_code' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Z0-9_\-]+$/',
                $teamCodeUniqueRule
            ],
            'team_location' => [
                'required',
                'string',
                Rule::in([
                    // Ahafo Region
                    'goaso', 'bechem', 'kenyasi',
                    // Ashanti Region
                    'kumasi', 'obuasi', 'konongo', 'ejura', 'mampong',
                    // Bono Region
                    'sunyani', 'dormaa-ahu', 'berekum',
                    // Bono East Region
                    'techiman', 'kintampo', 'nkoranza',
                    // Central Region
                    'cape-coast', 'elmina', 'winneba', 'agona-swedru',
                    // Eastern Region
                    'koforidua', 'nkawkaw', 'akim-oda', 'nsawam',
                    // Greater Accra Region
                    'accra', 'tema', 'madina', 'ashaiman', 'teshie', 'lapaz',
                    // North East Region
                    'nalerigu', 'walewale', 'chereponi',
                    // Northern Region
                    'tamale', 'yendi', 'saboba',
                    // Oti Region
                    'dambai', 'krachi-east', 'nkwanta',
                    // Savannah Region
                    'damongo', 'buipe', 'salaga',
                    // Upper East Region
                    'bolgatanga', 'navrongo', 'bawku',
                    // Upper West Region
                    'wa', 'jirapa', 'lawra',
                    // Volta Region
                    'ho', 'hohoe', 'kpando', 'sogakope',
                    // Western Region
                    'takoradi', 'sekondi', 'tarkwa', 'prestea',
                    // Western North Region
                    'sefwi-wiawso', 'bibiani', 'juaboso'
                ])
            ],
            'team_status' => [
                'required',
                'string',
                Rule::in(['active', 'inactive', 'deployed', 'maintenance'])
            ],
            
            // Team allocation details
            'team_allocation' => 'nullable|string|max:1000',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:team_members,id',
            'team_lead' => [
                'nullable',
                'exists:team_members,id',
                function ($attribute, $value, $fail) {
                    if ($value && $this->team_members && !in_array($value, $this->team_members)) {
                        $fail('The selected team lead must be one of the selected team members.');
                    }
                }
            ],
            
            // Vehicle assignments
            'assigned_vehicles' => 'nullable|array',
            'assigned_vehicles.*' => 'exists:vehicles,id',
            'primary_vehicle' => [
                'nullable',
                'exists:vehicles,id',
                function ($attribute, $value, $fail) {
                    if ($value && $this->assigned_vehicles && !in_array($value, $this->assigned_vehicles)) {
                        $fail('The selected primary vehicle must be one of the assigned vehicles.');
                    }
                }
            ],
            
            // Driver assignments
            'assigned_drivers' => 'nullable|array',
            'assigned_drivers.*' => 'exists:drivers,id',
            'primary_driver' => [
                'nullable',
                'exists:drivers,id',
                function ($attribute, $value, $fail) {
                    if ($value && $this->assigned_drivers && !in_array($value, $this->assigned_drivers)) {
                        $fail('The selected primary driver must be one of the assigned drivers.');
                    }
                }
            ],
            
            // Additional information
            'formation_date' => 'nullable|date|before_or_equal:today',
            'contact_number' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'team_name.required' => 'Team name is required.',
            'team_name.unique' => 'A team with this name already exists in your company.',
            'team_name.max' => 'Team name cannot exceed 255 characters.',
            
            'team_code.required' => 'Team code is required.',
            'team_code.unique' => 'This team code is already taken.',
            'team_code.regex' => 'Team code can only contain uppercase letters, numbers, hyphens, and underscores.',
            'team_code.max' => 'Team code cannot exceed 50 characters.',
            
            'team_location.required' => 'Team location is required.',
            'team_location.in' => 'Please select a valid team location.',
            
            'team_status.required' => 'Team status is required.',
            'team_status.in' => 'Please select a valid team status.',
            
            'team_allocation.max' => 'Team allocation description cannot exceed 1000 characters.',
            
            'team_members.array' => 'Team members must be provided as an array.',
            'team_members.*.exists' => 'One or more selected team members do not exist.',
            
            'team_lead.exists' => 'The selected team lead does not exist.',
            
            'assigned_vehicles.array' => 'Assigned vehicles must be provided as an array.',
            'assigned_vehicles.*.exists' => 'One or more selected vehicles do not exist.',
            
            'primary_vehicle.exists' => 'The selected primary vehicle does not exist.',
            
            'assigned_drivers.array' => 'Assigned drivers must be provided as an array.',
            'assigned_drivers.*.exists' => 'One or more selected drivers do not exist.',
            
            'primary_driver.exists' => 'The selected primary driver does not exist.',
            
            'formation_date.date' => 'Formation date must be a valid date.',
            'formation_date.before_or_equal' => 'Formation date cannot be in the future.',
            
            'contact_number.regex' => 'Contact number contains invalid characters.',
            'contact_number.max' => 'Contact number cannot exceed 20 characters.',
            
            'notes.max' => 'Notes cannot exceed 1000 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'team_name' => 'team name',
            'team_code' => 'team code',
            'team_location' => 'team location',
            'team_status' => 'team status',
            'team_allocation' => 'team allocation',
            'team_members' => 'team members',
            'team_lead' => 'team lead',
            'assigned_vehicles' => 'assigned vehicles',
            'primary_vehicle' => 'primary vehicle',
            'assigned_drivers' => 'assigned drivers',
            'primary_driver' => 'primary driver',
            'formation_date' => 'formation date',
            'contact_number' => 'contact number',
            'notes' => 'notes',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure team_members is an array
        if ($this->team_members && !is_array($this->team_members)) {
            $this->merge([
                'team_members' => [$this->team_members]
            ]);
        }

        // Ensure assigned_vehicles is an array
        if ($this->assigned_vehicles && !is_array($this->assigned_vehicles)) {
            $this->merge([
                'assigned_vehicles' => [$this->assigned_vehicles]
            ]);
        }

        // Ensure assigned_drivers is an array
        if ($this->assigned_drivers && !is_array($this->assigned_drivers)) {
            $this->merge([
                'assigned_drivers' => [$this->assigned_drivers]
            ]);
        }

        // Convert empty strings to null for optional fields
        $this->merge([
            'team_allocation' => $this->team_allocation ?: null,
            'team_lead' => $this->team_lead ?: null,
            'primary_vehicle' => $this->primary_vehicle ?: null,
            'primary_driver' => $this->primary_driver ?: null,
            'formation_date' => $this->formation_date ?: null,
            'contact_number' => $this->contact_number ?: null,
            'notes' => $this->notes ?: null,
        ]);
    }
}