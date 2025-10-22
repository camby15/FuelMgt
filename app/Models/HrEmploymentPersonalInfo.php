<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrEmploymentPersonalInfo extends Model
{
    use HasFactory;
    
    protected $table = 'hr_employment_personal_infos';
  
    protected $fillable = [
        'employee_id',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'primary_phone',
        'secondary_phone',
        'personal_email',
        'marital_status',
        'nationality',
        'country',
        'region',
        'city',
        'id_type_1',
        'id_number_1',
        'id_type_2',
        'id_number_2',
        'id_notes',
        'tin_number',
        'ssnit_number',
        'tax_status',
        'tax_exemption',
        'tax_notes',
        'profile_picture',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}