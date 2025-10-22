<?php

namespace App\Observers;

use App\Models\ProjectManagement\SiteAssignment;
use App\Models\ProjectManagement\AssignmentHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class SiteAssignmentObserver
{
    public function created(SiteAssignment $assignment)
    {
        $this->logAction($assignment, 'created');
    }

    public function updated(SiteAssignment $assignment)
    {
        // Check if status was changed
        if ($assignment->isDirty('status')) {
            $this->logStatusChange($assignment);
        } 
        // Check if issue was resolved
        elseif ($assignment->isDirty('issue_status') && $assignment->issue_status === 'resolved') {
            $this->logAction($assignment, 'issue_resolved');
        }
        // Check if issue was reported
        elseif ($assignment->isDirty('has_issue') && $assignment->has_issue) {
            $this->logAction($assignment, 'issue_reported');
        }
        // General update
        else {
            $this->logAction($assignment, 'updated');
        }
    }

    public function deleted(SiteAssignment $assignment)
    {
        $this->logAction($assignment, 'deleted');
    }

    protected function logAction(SiteAssignment $assignment, string $action)
    {
        $changes = $assignment->getChanges();
        
        // Remove timestamps from changes
        unset($changes['updated_at']);
        
        AssignmentHistory::create([
            'site_assignment_id' => $assignment->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'details' => $changes,
            'status_before' => $assignment->getOriginal('status'),
            'status_after' => $assignment->status,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    protected function logStatusChange(SiteAssignment $assignment)
    {
        AssignmentHistory::create([
            'site_assignment_id' => $assignment->id,
            'user_id' => Auth::id(),
            'action' => 'status_changed',
            'details' => [
                'from' => $assignment->getOriginal('status'),
                'to' => $assignment->status,
            ],
            'status_before' => $assignment->getOriginal('status'),
            'status_after' => $assignment->status,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
