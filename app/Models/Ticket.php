<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'customer',
        'priority',
        'subject',
        'category',
        'agent_id',
        'description',
        'status',
        'ticket_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $prefix = 'TKT';
            $latestTicket = static::withTrashed()->latest('id')->first();
            $nextId = $latestTicket ? $latestTicket->id + 1 : 1;
            $ticket->ticket_id = $prefix . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        });
    }

    public function agent()
    {
        return $this->belongsTo(Supportagent::class, 'agent_id');
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }
}