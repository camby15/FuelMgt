<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrEmploymentEmploymentInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'staff_id',
        'join_date',
        'department',
        'position',
        'supervisor_id',
        'employment_type',
        'probation_status',
        'employment_status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }
}