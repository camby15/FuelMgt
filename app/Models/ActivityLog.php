<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanyProfile;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'action_type',
        'model_type',
        'model_id',
        'description',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    /**
     * Log an activity
     *
     * @param string $actionType
     * @param string $modelType
     * @param int $modelId
     * @param string $description
     * @param array $metadata
     * @return ActivityLog
     */
    public static function log(
        string $actionType, 
        string $modelType, 
        int $modelId, 
        string $description, 
        array $metadata = []
    ) {
        return self::create([
            'user_id' => Auth::id(),
            'company_id' => session('selected_company_id'),
            'action_type' => $actionType,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'metadata' => $metadata
        ]);
    }

    /**
     * Get icon for activity type
     *
     * @return string
     */
    public function getIcon(): string
    {
        $iconMap = [
            'create' => 'fas fa-plus',
            'update' => 'fas fa-edit',
            'delete' => 'fas fa-trash',
            'view' => 'fas fa-eye',
            'login' => 'fas fa-sign-in-alt',
            'logout' => 'fas fa-sign-out-alt',
            'revenue' => 'fas fa-dollar-sign',
            'default' => 'fas fa-info-circle'
        ];

        return $iconMap[$this->action_type] ?? $iconMap['default'];
    }

    /**
     * Get icon background class
     *
     * @return string
     */
    public function getIconClass(): string
    {
        $classMap = [
            'create' => 'bg-success',
            'update' => 'bg-warning',
            'delete' => 'bg-danger',
            'view' => 'bg-info',
            'login' => 'bg-primary',
            'logout' => 'bg-secondary',
            'revenue' => 'bg-success',
            'default' => 'bg-light'
        ];

        return $classMap[$this->action_type] ?? $classMap['default'];
    }

    /**
     * Get icon background color
     *
     * @return string|null
     */
    public function getIconColor(): ?string
    {
        $colorMap = [
            'create' => '#28a745',    // Green
            'update' => '#ffc107',    // Yellow
            'delete' => '#dc3545',    // Red
            'view' => '#17a2b8',      // Teal
            'login' => '#007bff',     // Blue
            'logout' => '#6c757d',    // Gray
            'revenue' => '#28a745',   // Green
            'default' => '#4e73df'    // Indigo
        ];

        return $colorMap[$this->action_type] ?? $colorMap['default'];
    }

    /**
     * Get the company associated with this activity log
     */
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }
}
