<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CompanyProfile;
use App\Models\User;

class DeletedEmail extends Model
{
    protected $fillable = [
        'company_id',
        'sent_by_user_id',
        'recipient',
        'subject',
        'message',
        'status',
        'deleted_at'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by_user_id');
    }
}
