<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'crm_campaigns';

    protected $fillable = [
        'name',
        'type',
        'start_date',
        'end_date',
        'budget',
        'target_audience',
        'lead_goal',
        'conversion_goal',
        'roi_goal',
        'status',
        'company_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'deleted', // 0 = Not Deleted, 1 = Deleted
    ];

    protected $casts = [
        'target_audience' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'deleted' => 'boolean',
    ];

    // protected $dates = ['deleted_at'];

    /**
     * Relationship: Campaign has one detail record
     */
  
    public function campaignDetails()
    {
        return $this->hasOne(CampaignDetail::class, 'campaign_id');
    }

}

