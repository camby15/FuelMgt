<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\JobApplication;

class HRJob extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hrjobrecruitment';

    protected $fillable = [
        'company_id',
        'user_id',
        'token',
        'title',
        'department',
        'location',
        'type',
        'status',
        'posted_date',
        'applications',
        'description',
        'requirements',
    ];

    protected $dates = ['deleted_at'];

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_id');
    }
}
