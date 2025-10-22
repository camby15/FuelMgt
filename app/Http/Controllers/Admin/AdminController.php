<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\AddAgentRequest;
use App\Http\Requests\Agent\UpdateAgentRequest;
use App\Models\Supportagent;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Log;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    /**
     * list of all agents
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function agents()
    {

        $agents = Supportagent::withCount('ticket')->latest()->paginate(5);

        if ($agents->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No agents found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'agents' => $agents->items(),
            'pagination' => [
                'total' => $agents->total(),
                'current_page' => $agents->currentPage(),
                'last_page' => $agents->lastPage(),
                'per_page' => $agents->perPage(),
                'prev_page_url' => $agents->previousPageUrl(),
                'next_page_url' => $agents->nextPageUrl()
            ]
        ]);

    }

    /**
     * list of all tickets
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function ticket(Request $request)
    {
        $ticket = Ticket::with('agent')->latest()->paginate(5);


        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'ticket' => $ticket->items(),
            'pagination' => [
                'total' => $ticket->total(),
                'current_page' => $ticket->currentPage(),
                'last_page' => $ticket->lastPage(),
                'per_page' => $ticket->perPage(),
                'prev_page_url' => $ticket->previousPageUrl(),
                'next_page_url' => $ticket->nextPageUrl()
            ]
        ]);
    }

    /**
     * Summary of addAgent
     * @param \App\Http\Requests\Agent\AddAgentRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function addAgent(AddAgentRequest $request)
    {
        $data = $request->validated();

        $agent = Supportagent::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Agent added successfully',
            'agent' => $agent
        ]);
    }

    public function showTicket($id)
    {
        $ticket = Ticket::with('agent', 'attachments')->find($id);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'ticket' => $ticket
        ]);
    }

    /**
     * Summary of exportAgent
     * @return mixed|string|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportAgents(): StreamedResponse
    {
        $agents = Supportagent::withCount('ticket')->get();

        if ($agents->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No agents found'
            ], 404);
        }

        $filename = 'agents.xlsx';

        return response()->streamDownload(function () use ($agents) {
            (new FastExcel($agents))->export('php://output', function ($agent) {
                return [
                    'Name' => $agent->name,
                    'Email' => $agent->email,
                    'Phone' => $agent->phone,
                    'Tickets Count' => $agent->ticket_count,
                    'Status' => $agent->status,
                    'Created At' => $agent->created_at->format('Y-m-d H:i:s'),
                    'Updated At' => $agent->updated_at->format('Y-m-d H:i:s'),
                ];
            });
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="agents.xlsx"',
        ]);
    }

    /**
     * Summary of exportTicket
     * @return mixed|string|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportTickets(): StreamedResponse
    {
        $tickets = Ticket::with('agent')->get();

        if ($tickets->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No tickets found'
            ], 404);
        }


        $filename = 'tickets.xlsx';

        return response()->streamDownload(function () use ($tickets) {
            (new FastExcel($tickets))->export('php://output', function ($ticket) {
                return [
                    'Ticket ID' => $ticket->ticket_id,
                    'Customer Name' => $ticket->customer,
                    'Agent Name' => $ticket->agent->name,
                    'Subject' => $ticket->subject,
                    'Description' => $ticket->description,
                    'Status' => $ticket->status,
                    'Created At' => $ticket->created_at->format('Y-m-d H:i:s'),
                    'Updated At' => $ticket->updated_at->format('Y-m-d H:i:s'),
                ];
            });
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="tickets.xlsx"',
        ]);
    }

    /**
     * Summary of showTicket
     * @param mixed $id
     * @return void
     */
    public function destroyTicket($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found'
            ], 404);
        }

        $ticket->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ticket deleted successfully'
        ]);
    }

    /**
     * Summary of destroyAgent
     * @param mixed $id
     * @return void
     */
    public function destroyAgent($id)
    {
        $agent = Supportagent::find($id);

        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Agent not found'
            ], 404);
        }

        $agent->delete();

        return response()->json([
            'success' => true,
            'message' => 'Agent deleted successfully'
        ]);
    }

    /**
     *update Agent
     * @param \App\Http\Requests\Agent\UpdateAgentRequest $request
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */

    public function updateAgent(UpdateAgentRequest $request, $id)
    {
        $agent = Supportagent::find($id);

        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Agent not found'
            ], 404);
        }

        $data = $request->validated();

        $agent->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Agent updated successfully',
            'agent' => $agent
        ]);
    }

    /**
     * get Agent By Id
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAgentById($id)
    {
        $agent = Supportagent::find($id);

        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Agent not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'agent' => $agent
        ]);
    }

    /**
     * Import agents from an Excel file
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function importAgents(Request $request)
    {
        if (!$request->hasFile('importAgentFile')) {
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded'
            ], 400);
        }

        $file = $request->file('importAgentFile');

        if (!in_array($file->getClientOriginalExtension(), ['xlsx', 'xls', 'csv'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file format. Please upload an Excel or CSV file.'
            ], 400);
        }

        try {
            $imported = (new FastExcel)->import($file, function ($row) {
                $validator = Validator::make($row, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:support_agents,email',
                    'phone' => 'required|string|max:20',
                    'status' => 'required|in:available,unavailable'
                ]);

                if ($validator->fails()) {
                    Log::error('Validation failed for row:', $row);
                    Log::error('Validation errors:', $validator->errors()->toArray());

                    throw new \Exception("Validation failed for: " . json_encode($row));
                }

                return Supportagent::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                    'status' => $row['status'],
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Agents imported successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error importing agents: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error importing agents: ' . $e->getMessage()
            ], 500);
        }
    }






}

