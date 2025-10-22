<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Attendance extends Model
{
    use SoftDeletes;

    protected $table = 'hr_attendance';

    protected $fillable = [
        'company_id',
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'status',
        'late_minutes',
        'working_hours',
        'notes',
        'marked_by'
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime:H:i',
        'clock_out' => 'datetime:H:i',
    ];

    public function personalInfo()
    {
        return $this->belongsTo(HrEmploymentPersonalInfo::class, 'employee_id', 'employee_id');
    }

    public function employmentInfo()
    {
        return $this->belongsTo(HrEmploymentEmploymentInfo::class, 'employee_id', 'employee_id');
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}