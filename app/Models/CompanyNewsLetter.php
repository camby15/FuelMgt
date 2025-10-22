<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompanyNewsLetter extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_newsletters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // Template Details
        'company_id',
        'name',
        'subject',
        'content',
        'template_status',

        // Newsletter Campaign Details
        'send_date',
        'recipient_list',
        'campaign_status',
        
        // Performance Metrics
        'sent_count',
        'open_count',
        'click_count',
        'open_rate',
        'click_rate'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'send_date' => 'datetime',
        'recipient_list' => 'array',
        'sent_count' => 'integer',
        'open_count' => 'integer',
        'click_count' => 'integer',
        'open_rate' => 'float',
        'click_rate' => 'float'
    ];

    /**
     * Get the company that owns the newsletter.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    /**
     * Scope a query to only include active templates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActiveTemplates($query)
    {
        return $query->where('template_status', 'active');
    }

    /**
     * Scope a query to only include pending campaigns.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendingCampaigns($query)
    {
        return $query->where('campaign_status', 'pending');
    }

    /**
     * Check if the newsletter campaign is ready to be sent.
     *
     * @return bool
     */
    public function isReadyToSend(): bool
    {
        return $this->campaign_status === 'pending' && 
               $this->send_date <= now();
    }

    /**
     * Prepare and validate recipient list.
     *
     * @param array $recipients
     * @return $this
     */
    public function prepareRecipients(array $recipients)
    {
        // Validate and clean recipient list
        $validRecipients = array_filter($recipients, function($recipient) {
            return filter_var($recipient, FILTER_VALIDATE_EMAIL);
        });

        $this->recipient_list = $validRecipients;
        $this->sent_count = count($validRecipients);

        return $this;
    }

    /**
     * Mark newsletter campaign as sent.
     *
     * @return $this
     */
    public function markAsSent()
    {
        $this->campaign_status = 'sent';
        $this->save();

        return $this;
    }

    /**
     * Record an open event for the newsletter.
     *
     * @return $this
     */
    public function recordOpen()
    {
        $this->open_count++;
        $this->open_rate = $this->calculateOpenRate();
        $this->save();

        return $this;
    }

    /**
     * Record a click event for the newsletter.
     *
     * @return $this
     */
    public function recordClick()
    {
        $this->click_count++;
        $this->click_rate = $this->calculateClickRate();
        $this->save();

        return $this;
    }

    /**
     * Calculate open rate.
     *
     * @return float
     */
    protected function calculateOpenRate(): float
    {
        return $this->sent_count > 0 
            ? round(($this->open_count / $this->sent_count) * 100, 2) 
            : 0;
    }

    /**
     * Calculate click rate.
     *
     * @return float
     */
    protected function calculateClickRate(): float
    {
        return $this->sent_count > 0 
            ? round(($this->click_count / $this->sent_count) * 100, 2) 
            : 0;
    }

    /**
     * Get newsletter performance summary.
     *
     * @return array
     */
    public function getPerformanceSummary(): array
    {
        return [
            'sent_count' => $this->sent_count,
            'open_count' => $this->open_count,
            'click_count' => $this->click_count,
            'open_rate' => $this->open_rate,
            'click_rate' => $this->click_rate
        ];
    }
}