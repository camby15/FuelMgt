<?php

namespace App\Http\Controllers\CRM;

use App\Action\AutoAsignAgentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Models\CompanyProfile;
use App\Models\Supportagent;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\User;
use Faker\Provider\ar_EG\Company;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\AsignAgentMail;

class TicketController extends Controller
{

    public function index(Request $request)
    {
        try {

            $companyId = Session::get('selected_company_id');

            $perPage = $request->input('per_page', 5);

            $tickets = Ticket::with(['agent', 'attachments'])
                ->where('company_id', $companyId)
                ->orderBy('created_at', 'DESC')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'tickets' => $tickets->items(),
                'pagination' => [
                    'total' => $tickets->total(),
                    'per_page' => $tickets->perPage(),
                    'current_page' => $tickets->currentPage(),
                    'last_page' => $tickets->lastPage(),
                    'from' => $tickets->firstItem(),
                    't  o' => $tickets->lastItem()
                ],
                'meta' => [
                    'company_id' => $companyId,
                    'user_id' => Auth::id()
                ]

            ]);

        } catch (\Exception $e) {
            return $this->handleError('Failed to retrieve tickets', $e);
        }
    }

    public function show(Request $request)
    {
        try {
            $ticket = Ticket::with(['agent', 'attachments'])
                ->where('company_id', $this->getCompanyId())
                ->where('id', $request->id)
                ->firstOrFail();

            \Log::info('Ticket retrieved successfully', [
                'ticket_id' => $ticket->ticket_id,
                'company_id' => $ticket->company_id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'ticket' => $ticket,
                'meta' => [
                    'company_id' => $this->getCompanyId(),
                    'user_id' => Auth::id()
                ]
            ]);

        } catch (\Exception $e) {
            return $this->handleError('Failed to retrieve ticket', $e, 404);
        }

    }


    /**
     * Create a TICKET
     * @param \App\Http\Requests\Ticket\StoreTicketRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreTicketRequest $request)
    {
        try {
            $data = $request->validated();

            $data['user_id'] = Auth::id();

            $company = CompanyProfile::where('user_id', Auth::id())->firstOrFail();
            $data['company_id'] = $company->id;
            $data['customer'] = $company->company_name;

            $ticket = Ticket::create($data);

            if (!empty($data['agent_id'])) {
                $agent = Supportagent::where('id', $data['agent_id'])->firstOrFail();
                $data['agent_id'] = $agent->id;

                Mail::to($agent->email)->queue(new AsignAgentMail($ticket));
            }

            $this->handleAttachments($request, $ticket);

            if (empty($data['agent_id'])) {
                AutoAsignAgentAction::execute($ticket->ticket_id);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ticket created successfully!',
                'ticket_id' => $ticket->ticket_id,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create ticket: ' . $e->getMessage());
            return $this->handleError('Failed to create ticket', $e);
        }
    }

    /**
     * close a TICKET
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function close($id)
    {
        try {

            $ticket = Ticket::with(['attachments'])
                ->where('company_id', $this->getCompanyId())
                ->where('id', $id)
                ->firstOrFail();


            $this->verifyCompanyOwnership($ticket);

            $ticket->update([
                'status' => 'closed',
            ]);
            $ticket->save();

            return response()->json([
                'success' => true,
                'message' => "Ticket status set to closed successfully.",
                'meta' => [
                    'company_id' => $ticket->company_id,
                    'user_id' => Auth::id()
                ]
            ]);
        } catch (\Exception $e) {
            return $this->handleError('Failed to close ticket', $e);
        }
    }

    public function tickets()
    {
        try {

            $tickets = Ticket::with('attachments')->get();

            return response()->json([
                'data' => $tickets
            ]);

        } catch (\Throwable $th) {

            throw $th;
        }

    }






    // Helper Methods

    protected function getCompanyId()
    {
        $companyId = Session::get('selected_company_id');
        if (!$companyId) {
            throw new \Exception('Company session expired');
        }
        return $companyId;
    }

    protected function verifyCompanyOwnership(Ticket $ticket)
    {
        $companyId = $this->getCompanyId();
        if ($ticket->company_id != $companyId) {
            throw new \Exception('Unauthorized access to ticket');
        }
    }

    protected function handleAttachments(Request $request, Ticket $ticket)
    {
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-attachments', 'public');

                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'company_id' => $ticket->company_id,
                    'user_id' => Auth::id()
                ]);
            }
        }
    }

    protected function handleError($message, \Exception $e, $status = 500)
    {
        Log::error($message . ': ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => $e->getMessage(),
            'meta' => [
                'company_id' => Session::get('selected_company_id'),
                'user_id' => Auth::id()
            ]
        ], $status);
    }
}