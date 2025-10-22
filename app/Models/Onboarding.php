<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Onboarding extends Model
{
    use HasFactory;

    protected $table = 'onboarding';

    protected $fillable = [
        'employee_id',
        'offer_accepted_date',
        'documents_uploaded_status',
        'documents_uploaded_date',
        'staff_id_assigned_status',
        'staff_id_assigned_date',
        'first_day_checklist_status',
        'first_day_checklist_date',
        'start_date',
        'manager_id',
        'overall_status',
        'notes'
    ];

    protected $casts = [
        'offer_accepted_date' => 'date',
        'documents_uploaded_date' => 'date',
        'staff_id_assigned_date' => 'date',
        'first_day_checklist_date' => 'date',
        'start_date' => 'date',
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    // Helper methods
    public function getProgressPercentage()
    {
        $steps = [
            'offer_accepted_date',
            'documents_uploaded_status',
            'staff_id_assigned_status',
            'first_day_checklist_status'
        ];

        $completed = 0;
        foreach ($steps as $step) {
            if ($step === 'offer_accepted_date' && $this->offer_accepted_date) {
                $completed++;
            } elseif ($step === 'documents_uploaded_status' && $this->documents_uploaded_status === 'completed') {
                $completed++;
            } elseif ($step === 'staff_id_assigned_status' && $this->staff_id_assigned_status === 'completed') {
                $completed++;
            } elseif ($step === 'first_day_checklist_status' && $this->first_day_checklist_status === 'completed') {
                $completed++;
            }
        }

        return round(($completed / count($steps)) * 100);
    }

    public function getCompletedTasksCount()
    {
        $steps = [
            'offer_accepted_date',
            'documents_uploaded_status',
            'staff_id_assigned_status',
            'first_day_checklist_status'
        ];

        $completed = 0;
        foreach ($steps as $step) {
            if ($step === 'offer_accepted_date' && $this->offer_accepted_date) {
                $completed++;
            } elseif ($step === 'documents_uploaded_status' && $this->documents_uploaded_status === 'completed') {
                $completed++;
            } elseif ($step === 'staff_id_assigned_status' && $this->staff_id_assigned_status === 'completed') {
                $completed++;
            } elseif ($step === 'first_day_checklist_status' && $this->first_day_checklist_status === 'completed') {
                $completed++;
            }
        }

        return $completed;
    }
}
