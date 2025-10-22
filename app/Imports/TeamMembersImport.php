<?php

namespace App\Imports;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class TeamMembersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    protected $companyId;
    protected $createdBy;

    public function __construct()
    {
        $this->companyId = Session::get('selected_company_id');
        $this->createdBy = Auth::id();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new TeamMember([
            'company_id' => $this->companyId,
            'full_name' => $row['full_name'],
            'employee_id' => $row['employee_id'],
            'position' => $row['position'],
            'department' => strtolower($row['department']),
            'phone' => $row['phone'],
            'email' => $row['email'],
            'hire_date' => $row['hire_date'] ? \Carbon\Carbon::createFromFormat('Y-m-d', $row['hire_date']) : null,
            'status' => strtolower(str_replace(' ', '-', $row['status'])),
            'notes' => $row['notes'] ?? null,
            'created_by' => $this->createdBy,
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.full_name' => 'required|string|max:255',
            '*.employee_id' => 'required|string|max:255',
            '*.position' => 'required|string|max:255',
            '*.department' => 'required|in:Technical,Operations,Maintenance,Administration,technical,operations,maintenance,administration',
            '*.phone' => 'required|string|max:20',
            '*.email' => 'required|email|max:255',
            '*.hire_date' => 'nullable|date',
            '*.status' => 'required|in:Active,Inactive,On Leave,active,inactive,on-leave',
            '*.notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            '*.full_name.required' => 'Full name is required.',
            '*.employee_id.required' => 'Employee ID is required.',
            '*.position.required' => 'Position is required.',
            '*.department.required' => 'Department is required.',
            '*.department.in' => 'Department must be one of: Technical, Operations, Maintenance, Administration.',
            '*.phone.required' => 'Phone number is required.',
            '*.email.required' => 'Email is required.',
            '*.email.email' => 'Email must be a valid email address.',
            '*.status.required' => 'Status is required.',
            '*.status.in' => 'Status must be one of: Active, Inactive, On Leave.',
        ];
    }
}
