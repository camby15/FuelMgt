<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrEmploymentBankInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'bank_name',
        'branch_name',
        'account_number',
        'account_type',
        'currency',
        'ezwich_number',
        'mobile_network',
        'mobile_number',
        'mobile_name',
        'bank_statement',
        'verification_status',
        'bank_notes',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}