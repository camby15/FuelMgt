<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrEmploymentEmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'primary_emergency_name',
        'primary_emergency_relation',
        'primary_emergency_phone',
        'primary_emergency_email',
        'primary_emergency_alt_phone',
        'primary_emergency_address',
        'secondary_emergency_name',
        'secondary_emergency_relation',
        'secondary_emergency_phone',
        'secondary_emergency_email',
        'secondary_emergency_alt_phone',
        'secondary_emergency_address',
        'blood_group',
        'nhis_number',
        'known_allergies',
        'allergy_details',
        'medical_conditions',
        'special_needs',
    ];

    protected $casts = [
        'known_allergies' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}