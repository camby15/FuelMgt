<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportAgent extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'support_agents';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
    ];

    public function ticket()
    {
        return $this->hasMany(Ticket::class, 'agent_id');
    }


}
