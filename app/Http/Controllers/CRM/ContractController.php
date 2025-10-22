<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractAuditTrail;
use App\Mail\ContractFormMail; 
use App\Mail\SignatureFormMail; 
use App\Models\ContractSignature;
use App\Models\ContractAttachment;
use App\Models\ContractComment;
use App\Models\ContractReminder;
use App\Models\ActivityLog;
use App\Models\ContractActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Validation\ValidationException;

class ContractController extends Controller
{

public function index()
{
    try {
        $contracts = Contract::with(['creator', 'signatures'])->latest()->paginate(5);

        $recentActivities = ContractActivity::where('model_type', 'App\Models\Contract')
            ->latest()
            ->get(); // No pagination as requested

        return view('company.CRM.contract', compact('contracts', 'recentActivities'));
    } catch (\Exception $e) {
        Log::error('Contract index error: ' . $e->getMessage());
        return back()->with('error', 'Unable to load contracts.');
    }
}



    /**
     * Store a new contract
     */
public function store(Request $request)
{
    try {

          // Step 1: Convert JSON string to array if needed
        $emailsRaw = $request->input('emails');
        $emailsArray = is_string($emailsRaw) ? json_decode($emailsRaw, true) : $emailsRaw;
        $request->merge(['emails' => $emailsArray]); // Ensure it's an array for validation

        $request->validate([
            'name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'emails' => ['required', 'array', 'min:1'], // array required
            'emails.*' => ['required', 'email'],        // each must be valid email
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string',
            'signature_file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
            'auto_renewal' => 'nullable',
        ]);

        $autoRenewal = $request->has('auto_renewal') ? 1 : 0;

        $publicPath = null;
        if ($request->hasFile('signature_file')) {
            $file = $request->file('signature_file');
            $filename = uniqid('sig_') . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('signatures', $filename, 'public');
            $publicPath = 'storage/' . $filePath;
        }

        // Clean and unique emails
        $emailsArray = array_unique(array_filter($request->input('emails')));

        $contract = Contract::create([
            'name' => $request->name,
            'customer_name' => $request->customer_name,
            'emails' => $emailsArray, // stored as JSON
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'notes' => $request->notes,
            'status' => 'draft',
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'auto_renewal' => $autoRenewal,
        ]);

        foreach ($emailsArray as $email) {
            ContractSignature::create([
                'contract_id' => $contract->id,
                'signer_name' => $request->customer_name,
                'signer_email' => $email,
                'status' => 'pending',
                'created_by' => Auth::id(),
                'signature_image_path' => $publicPath,
            ]);
        }

        ContractActivity::create([
            'user_id'      => auth()->id(),
            'company_id'   => $contract->company_id ?? null,
            'action_type'  => 'create',
            'model_type'   => Contract::class,
            'model_id'     => $contract->id,
            'description'  => 'New contract created',
            'metadata'     => [
                'name'           => $request->customer_name,
                'customer_name'  => $request->customer_name,
                'emails'         => $emailsArray,
                'auto_renewal'   => $autoRenewal,
                'contract_name'  => $request->name,
            ],
        ]);

        $this->logAuditTrail($contract->id, 'Contract created');

        return response()->json([
            'success' => true,
            'message' => 'Contract created successfully',
            'contract' => $contract
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed: ', $e->errors());
        return response()->json([
            'success' => false,
            'message' => 'Validation error.',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Contract creation failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to create contract'
        ], 500);
    }
}






    /**
     * Get a specific contract
     */
    public function show($id)
    {
        try {
            $contract = Contract::with(['creator', 'signatures'])->findOrFail($id);
            
            // Format dates for frontend
            $contract->start_date = date('Y-m-d', strtotime($contract->start_date));
            $contract->end_date = date('Y-m-d', strtotime($contract->end_date));
            
            return response()->json([
                'success' => true,
                'contract' => $contract
            ]);
        } catch (Exception $e) {
            Log::error('Contract fetch failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch contract details'
            ], 404);
        }
    }

    /**
 * Update an existing contract
 */
public function update(Request $request, $id)
{
    $request->validate([
        'contract_name' => 'required|string|max:255',
        'customer_name' => 'required|string|max:255',
        'email' => 'required|email',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'notes' => 'nullable|string',
        'contract_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240'
    ]);

    try {
        $contract = Contract::findOrFail($id);

        if ($request->hasFile('contract_file')) {
            // Delete old file if exists
            if ($contract->file_path) {
                Storage::delete($contract->file_path);
            }
            $filePath = $request->file('contract_file')->store('contracts');
            $contract->file_path = $filePath;

            ContractAttachment::create([
                'contract_id' => $contract->id,
                'file_name' => $request->file('contract_file')->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $request->file('contract_file')->getClientMimeType(),
                'file_size' => $request->file('contract_file')->getSize(),
                'uploaded_by' => Auth::id()
            ]);
        }

        $contract->update([
            'name' => $request->contract_name,
            'customer_name' => $request->customer_name,
            'email' => $request->email,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'notes' => $request->notes,
            'updated_by' => Auth::id()
        ]);

        // ✅ Log the contract update
        ContractActivity::create([
            'user_id'      => auth()->id(),                     // or use a specific ID
            'company_id'   => $contract->company_id ?? null,    // make sure this exists
            'action_type'  => 'update',
            'model_type'   => Contract::class,
            'model_id'     => $contract->id,
            'description'  => 'Contract updated',
            'metadata'     => [
                'contract_name' => $request->contract_name,
                'customer_name' => $request->customer_name,
                'email' => $request->email
            ]
        ]);

        $this->logAuditTrail($contract->id, 'Contract updated');

        return response()->json([
            'success' => true,
            'message' => 'Contract updated successfully',
            'contract' => $contract
        ]);
    } catch (Exception $e) {
        Log::error('Contract update failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to update contract'
        ], 500);
    }
}

    /**
 * Delete a contract
 */
public function destroy($id)
{
    try {
        $contract = Contract::findOrFail($id);
        
        // Delete associated file if exists
        if ($contract->file_path) {
            Storage::delete($contract->file_path);
        }

        // Delete associated attachments
        foreach ($contract->attachments as $attachment) {
            Storage::delete($attachment->file_path);
        }

        $contract->delete();

        // ✅ Log the contract deletion
        ContractActivity::create([
            'user_id'      => auth()->id(),                     // or use a specific ID
            'company_id'   => $contract->company_id ?? null,    // make sure this exists
            'action_type'  => 'delete',
            'model_type'   => Contract::class,
            'model_id'     => $contract->id,
            'description'  => 'Contract deleted',
            'metadata'     => [
                'contract_name' => $contract->name,
                'customer_name' => $contract->customer_name,
                'email' => $contract->email,
                'contract_name' => $contract->name,
            ]
        ]);

        $this->logAuditTrail($id, 'Contract deleted');

        return response()->json([
            'success' => true,
            'message' => 'Contract deleted successfully'
        ]);
    } catch (Exception $e) {
        Log::error('Contract deletion failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete contract'
        ], 500);
    }
}

    /**
     * Send contract for signature
     */
    public function sendForSignature(Request $request, $id)
    {
        $request->validate([
            'signer_email' => 'required|email',
            'signer_name' => 'required|string|max:255',
            'message' => 'nullable|string'
        ]);

        try {
            $contract = Contract::findOrFail($id);
            
            // Check if contract has a file
            if (!$contract->file_path || !Storage::exists($contract->file_path)) {
                throw new Exception('Contract file not found');
            }

            // Create signature request record
            $signature = ContractSignature::create([
                'contract_id' => $contract->id,
                'signer_name' => $request->signer_name,
                'signer_email' => $request->signer_email,
                'message' => $request->message,
                'status' => 'pending',
                'created_by' => Auth::id()
            ]);

            // Generate unique signature link
            $signatureToken = \Str::random(64);
            $signature->update(['signature_token' => $signatureToken]);

            // Send email to signer with signature link
            // TODO: Implement email sending logic here
            // Mail::to($request->signer_email)->send(new ContractSignatureRequest($contract, $signature));

            // Update contract status
            $contract->update(['status' => 'pending_signature']);

            // Log the action
            $this->logAuditTrail($contract->id, 'Contract sent for signature to ' . $request->signer_email);

            return response()->json([
                'success' => true,
                'message' => 'Contract sent for signature successfully'
            ]);
        } catch (Exception $e) {
            Log::error('Send for signature failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send contract for signature: ' . $e->getMessage()
            ], 500);
        }
    }


    public function filter(Request $request)
    {
        try {
            $contracts = Contract::query();

            if ($request->has('status')) {
                $contracts->where('status', $request->status);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $contracts->where(function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('customer_name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $contracts = $contracts->latest()->get();

            return response()->json([
                'success' => true,
                'html' => view('company.CRM.partials.contract-table', compact('contracts'))->render()
            ]);
        } catch (Exception $e) {
            Log::error('Contract filtering failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to filter contracts'
            ], 500);
        }
    }

    /**
     * Add a comment to a contract
     */
    public function addComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string'
        ]);

        try {
            $contract = Contract::findOrFail($id);
            
            ContractComment::create([
                'contract_id' => $contract->id,
                'user_id' => Auth::id(),
                'comment' => $request->comment
            ]);

            $this->logAuditTrail($contract->id, 'Comment added');

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully'
            ]);
        } catch (Exception $e) {
            Log::error('Adding comment failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment'
            ], 500);
        }
    }

    /**
     * Add a reminder to a contract
     */
    public function addReminder(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'reminder_date' => 'required|date|after:today',
            'reminder_type' => 'required|in:expiration,renewal,payment,custom',
            'description' => 'nullable|string'
        ]);

        try {
            $contract = Contract::findOrFail($id);
            
            ContractReminder::create([
                'contract_id' => $contract->id,
                'title' => $request->title,
                'description' => $request->description,
                'reminder_date' => $request->reminder_date,
                'reminder_type' => $request->reminder_type,
                'created_by' => Auth::id()
            ]);

            $this->logAuditTrail($contract->id, 'Reminder added');

            return response()->json([
                'success' => true,
                'message' => 'Reminder added successfully'
            ]);
        } catch (Exception $e) {
            Log::error('Adding reminder failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add reminder'
            ], 500);
        }
    }

    /**
     * Get audit trail for a contract
     */
    public function getAuditTrail($id)
    {
        try {
            $auditTrail = ContractAuditTrail::where('contract_id', $id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'auditTrail' => $auditTrail
            ]);
        } catch (Exception $e) {
            Log::error('Fetching audit trail failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch audit trail'
            ], 500);
        }
    }

    /**
     * Download a contract file
     */
    public function download($id)
    {
        try {
            $contract = Contract::findOrFail($id);

            // Debug logs - add these lines
            Log::info('Attempting to download contract ID: '.$id);
            Log::info('File path: '.$contract->file_path);
            Log::info('File exists: '.(Storage::exists($contract->file_path) ? 'Yes' : 'No'));
            Log::info('Storage disk: '.config('filesystems.default'));

            if (!$contract->file_path || !Storage::exists($contract->file_path)) {
                throw new Exception('Contract file not found');
            }

            $this->logAuditTrail($contract->id, 'Contract downloaded');

            return Storage::download(
                $contract->file_path, 
                $contract->name . '_' . date('Y-m-d') . '.' . pathinfo($contract->file_path, PATHINFO_EXTENSION)
            );
        } catch (Exception $e) {
            Log::error('Contract download failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to download contract file.');
        }
    }

    /**
     * Log audit trail for a contract
     */
    private function logAuditTrail($contractId, $action)
    {
        ContractAuditTrail::create([
            'contract_id' => $contractId,
            'user_id' => Auth::id(),
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    
    /**
     * Send signature link 
     */
 public function sendSignature(Request $request)
{
    $validated = $request->validate([
        'contract_id' => 'required|integer|exists:contracts,id',
        'emails' => 'required|array|min:1',
        'emails.*' => 'required|email',
    ]);

    $contractId = $validated['contract_id'];
    $emails = $validated['emails'];

    $contract = Contract::find($contractId);

    if (!$contract) {
        throw ValidationException::withMessages([
            'contract_id' => ['No contract found with this ID.'],
        ]);
    }

    $sent = [];
    $failed = [];

    foreach ($emails as $recipient) {
        try {
            // 1. Generate secure signature link
            $signatureLink = route('client.signature.form', [
                'email' => $recipient,
                'id' => $contract->id,
                'token' => Str::random(32),
            ]);

            // 2. Fetch signatures for this contract and convert to base64
            $signatures = ContractSignature::where('contract_id', $contract->id)->get()->map(function ($sig) {
                $path = storage_path('app/public/' . $sig->signature_image_path);

                if (file_exists($path)) {
                    $imageData = base64_encode(file_get_contents($path));
                    $sig->signature_base64 = 'data:image/png;base64,' . $imageData;
                } else {
                    $sig->signature_base64 = null;
                }

                return $sig;
            });

            // 3. Render PDF content
            $pdfContent = Pdf::loadView('pdf.contract-details', [
                'contract' => $contract,
                'recipient' => $recipient,
                'signatures' => $signatures,
            ])->setPaper('a4')->output();

            // 4. Store PDF in organized directory
            $pdfDirectory = 'contracts/' . date('Y/m/d');
            $pdfFilename = Str::uuid() . '.pdf';
            $relativePath = "$pdfDirectory/$pdfFilename";
            $absolutePath = storage_path("app/$relativePath");

            // Create directory if it doesn't exist
            Storage::disk('local')->makeDirectory($pdfDirectory);

            if (file_put_contents($absolutePath, $pdfContent) === false) {
                throw new \Exception("Failed to write PDF to disk at $absolutePath");
            }

            if (!file_exists($absolutePath)) {
                throw new \Exception("PDF file not found after writing: $absolutePath");
            }

            Log::debug("PDF saved", [
                'recipient' => $recipient,
                'path' => $absolutePath,
                'size' => strlen($pdfContent),
            ]);

            // 5. Send queued email with **relative** path
            Mail::to($recipient)->queue(new SignatureFormMail(
                $signatureLink,
                $contract->name,
                $relativePath // pass relative path here, NOT absolute
            ));

            $sent[] = $recipient;
            Log::info("Queued email", ['recipient' => $recipient]);

        } catch (\Throwable $e) {
            $failed[] = $recipient;
            Log::error("Signature process failed", [
                'recipient' => $recipient,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Attempt to clean up any created PDF
            if (isset($absolutePath) && file_exists($absolutePath)) {
                try {
                    unlink($absolutePath);
                    Log::info("Cleaned up failed PDF", ['path' => $absolutePath]);
                } catch (\Throwable $cleanupError) {
                    Log::error("Cleanup failed", [
                        'path' => $absolutePath,
                        'error' => $cleanupError->getMessage(),
                    ]);
                }
            }
        }
    }

    // 6. Final response JSON
    if (count($sent) === 0) {
        return response()->json([
            'type' => 'error',
            'title' => 'Failure',
            'message' => 'Failed to send signature links to all recipients.',
            'failed' => $failed,
        ], 500);
    } elseif (count($failed) > 0) {
        return response()->json([
            'type' => 'warning',
            'title' => 'Partial Success',
            'message' => 'Some emails failed to send.',
            'sent' => $sent,
            'failed' => $failed,
        ], 207);
    }

    return response()->json([
        'type' => 'success',
        'title' => 'Success!',
        'message' => 'Signature links sent to all recipients.',
    ]);
}



    
}

