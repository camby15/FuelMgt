<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractActivity extends Model
{
    use HasFactory;

    protected $table = 'contract_activity'; // explicitly map table name

    protected $fillable = [
        'user_id',
        'company_id',
        'action_type',
        'model_type',
        'model_id',
        'description',
        'metadata',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public $timestamps = true; // since you have created_at/updated_at
}
