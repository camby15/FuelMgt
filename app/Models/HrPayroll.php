<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrPayroll extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'hr_payroll';
    protected $fillable = [
        'company_id',
        'employee_id',
        'pay_period',
        'start_date',
        'end_date',
        'payment_date',
        'basic_salary',
        'housing_allowance',
        'transport_allowance',
        'overtime',
        'bonus',
        'other_allowances',
        'ssnit',
        'paye',
        'tier2_pension',
        'other_deductions',
        'gross_pay',
        'total_deductions',
        'net_pay',
        'status',
        'payment_method',
        'payment_reference',
        'notes',
        'processed_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'payment_date' => 'date',
        'basic_salary' => 'decimal:2',
        'housing_allowance' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'overtime' => 'decimal:2',
        'bonus' => 'decimal:2',
        'other_allowances' => 'decimal:2',
        'ssnit' => 'decimal:2',
        'paye' => 'decimal:2',
        'tier2_pension' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'gross_pay' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_pay' => 'decimal:2'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}