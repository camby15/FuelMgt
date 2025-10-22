<?php

namespace App\Action;


use App\Models\Ticket;
use App\Models\Supportagent;
use App\Mail\AsignAgentMail;
use Illuminate\Support\Facades\Mail;

class AutoAsignAgentAction
{
    public static function execute($ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        $agent = Supportagent::withCount('ticket')
            ->orderBy('ticket_count', 'asc')
            ->first();

        if (!$agent) {
            abort(404, 'No agents available for assignment');
        }


        $ticket->agent_id = $agent->id;
        $ticket->save();

        // Send notification email
        if ($agent->email) {
            Mail::to($agent->email)->send(new AsignAgentMail($ticket));

        }


        return $ticket;
    }
}
