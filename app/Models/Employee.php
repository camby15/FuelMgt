<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'employees';

    protected $fillable = [
        'company_id',
        'user_id',
        'staff_id',
        'email',
        'status',
    ];

    public function personalInfo()
    {
        return $this->hasOne(HrEmploymentPersonalInfo::class, 'employee_id');
    }

    public function employmentInfo()
    {
        return $this->hasOne(HrEmploymentEmploymentInfo::class, 'employee_id');
    }

    public function bankInfo()
    {
        return $this->hasOne(HrEmploymentBankInfo::class, 'employee_id');
    }

    public function emergencyContact()
    {
        return $this->hasOne(HrEmploymentEmergencyContact::class, 'employee_id');
    }

    public function documents()
    {
        return $this->hasOne(HrEmploymentDocuments::class, 'employee_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}