<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ActivityLogController extends Controller
{
    /**
     * Get recent activity logs for the current company
     *
     * @param string|null $search
     * @return \Illuminate\Support\Collection
     */
    public static function getRecentActivities($search = null)
    {
        $companyId = Session::get('selected_company_id');

        $query = ActivityLog::where('company_id', $companyId)
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('metadata', 'like', "%{$search}%")
                  ->orWhere('action_type', 'like', "%{$search}%")
                  ->orWhere('model_type', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }

    /**
     * Log a new activity
     *
     * @param string $actionType
     * @param string $modelType
     * @param int $modelId
     * @param string $description
     * @param array $metadata
     * @return ActivityLog
     */
    public static function logActivity(
        string $actionType, 
        string $modelType, 
        int $modelId, 
        string $description, 
        array $metadata = []
    ) {
        return ActivityLog::log(
            $actionType, 
            $modelType, 
            $modelId, 
            $description, 
            $metadata
        );
    }
}
