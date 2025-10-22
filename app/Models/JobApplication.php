<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'region',
        'country',
        'nationality',
        'education_level',
        'experience_years',
        'cover_letter',
        'resume_path',
        'status',
        'notes',
        'applied_at'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'applied_at' => 'datetime',
    ];

    public function job()
    {
        return $this->belongsTo(HRJob::class, 'job_id');
    }
}
