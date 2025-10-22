<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesTimeline extends Model
{
    use HasFactory; 

    protected $table = 'crm_sales_timelines';

    protected $fillable = [
        "user_id",
        "company_id",
        "sales_id",
        "timeline_action",
        "timeline_vales"
    ];
}
