<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignDetail extends Model
{
    use HasFactory;

    protected $table = 'crm_campaign_details';

    protected $fillable = [
        'campaign_id',
        'goals',
        'notes',
        'documents', // Added documents field
        'kpis',
        'created_by',
        'assigned_users'
    ];

    protected $casts = [
        'goals' => 'string',
        'notes' => 'json', // Now stores multiple notes
        'documents' => 'json', // Now stores multiple document paths
        'kpis' => 'json',
        'assigned_users' => 'json',
    ];

    /**
     * Relationship: Each detail belongs to one campaign.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
