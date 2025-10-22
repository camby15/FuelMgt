<?php

namespace App\Models\ProjectManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class AssignmentHistory extends Model
{
    protected $table = 'assignment_histories';

    protected $fillable = [
        'site_assignment_id',
        'user_id',
        'action',
        'details',
        'status_before',
        'status_after',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    // Relationships
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(SiteAssignment::class, 'site_assignment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper Methods
    public function getActionBadgeAttribute(): string
    {
        $badgeClass = [
            'created' => 'bg-success',
            'updated' => 'bg-primary',
            'status_changed' => 'bg-info',
            'issue_reported' => 'bg-warning',
            'issue_resolved' => 'bg-success',
            'deleted' => 'bg-danger',
        ][$this->action] ?? 'bg-secondary';

        return '<span class="badge ' . $badgeClass . '">' . ucfirst(str_replace('_', ' ', $this->action)) . '</span>';
    }

    // Scopes
    public function scopeForAssignment($query, $assignmentId)
    {
        return $query->where('site_assignment_id', $assignmentId)
                    ->latest();
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->take($limit);
    }
}
