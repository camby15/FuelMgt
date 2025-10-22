<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrPayrollRun extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'company_id',
        'pay_period',
        'start_date',
        'end_date',
        'employee_selection',
        'department',
        'selected_employees',
        'include_bonuses',
        'include_deductions',
        'status',
        'created_by',
        'notes'
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'selected_employees' => 'array',
        'include_bonuses' => 'boolean',
        'include_deductions' => 'boolean'
    ];
    
    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
