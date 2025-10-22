<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contra;
use App\Models\Contract;
use App\Models\ContractSignature;
use App\Models\ContractActivity;
use App\Models\ContractAttachment;
use App\Http\Controllers\ActivityLogController;
use App\Mail\FinalContractMail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignatureFormMail;
use Illuminate\Support\Str;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;


class ContractFormController extends Controller
{
    //

    private function logAuditTrail($contractId, $message)
    {
        Log::info("Audit Trail - Contract ID: $contractId, Message: $message");
    }


    public function showContractForm()
    {
        return view('external/client-contractForm');
    }

    



    public function showSignatureForm(Request $request, $email, $id)
    {
        return view('external.client-signatureForm', compact('email', 'id'));
    }


    //  submitting the signature form
    public function submitSignatureForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contract_id' => 'required|integer|exists:contracts,id',
            'email' => 'required|string|max:255',
            'signature_option' => 'required|in:draw,upload',
            'signature' => 'required_if:signature_option,draw',
            'signature_file' => 'required_if:signature_option,upload|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $contract = Contract::findOrFail($request->contract_id);
            $email = strtolower(trim($request->email));
            $fileName = null;

            // Normalize contract emails
            $emailsRaw = $contract->emails;
            if (is_string($emailsRaw)) {
            $emails = array_map(fn($e) => strtolower(trim($e)), explode(',', $emailsRaw));
            } elseif (is_array($emailsRaw)) {
            $emails = array_map(fn($e) => strtolower(trim($e)), $emailsRaw);
            } else {
            throw new \Exception("Unexpected format for contract emails: must be string or array");
            }

            // Check if the submitted email exists in contract emails
            if (!in_array($email, $emails)) {
            return response()->json([
                'success' => false,
                'message' => 'This email is not authorized to sign this contract.'
            ], 403);
            }

            if ($request->signature_option === 'draw') {
            $signatureData = $request->input('signature');
            if (!preg_match('/^data:image\/(\w+);base64,/', $signatureData, $type)) {
                return response()->json(['success' => false, 'message' => 'Invalid signature format.'], 400);
            }
            $signatureData = substr($signatureData, strpos($signatureData, ',') + 1);
            $type = strtolower($type[1]);
            if (!in_array($type, ['jpg', 'jpeg', 'png'])) throw new \Exception('Unsupported image type: ' . $type);
            $decodedImage = base64_decode($signatureData);
            if ($decodedImage === false) throw new \Exception('Base64 decoding failed');
            $fileName = 'signatures/' . uniqid('sig_') . '.' . $type;
            Storage::disk('public')->put($fileName, $decodedImage);
            }

            if ($request->signature_option === 'upload') {
            $file = $request->file('signature_file');
            $fileName = $file->store('signatures', 'public');
            }

            $publicPath =   $fileName;

            $signature = ContractSignature::where('contract_id', $contract->id)
            ->where('signer_email', $email)
            ->first();

            if ($signature) {
            return response()->json([
                'success' => false,
                'message' => 'This email has already signed the contract.'
            ], 400);
            } else {
            ContractSignature::create([
                'contract_id' => $contract->id,
                'signature_image_path' => $publicPath,
                'status' => 'signed',
                'signer_name' => $request->name,
                'signer_email' => $email,
                'created_by' => $request->name,
            ]);
            }

            ContractActivity::create([
            'user_id'      => auth()->id(),
            'company_id'   => $contract->company_id ?? null,
            'action_type'  => 'Sign',
            'model_type'   => Contract::class,
            'model_id'     => $contract->id,
            'description'  => 'Contract signed by customer',
            'metadata'     => [
                'contract_name' => $contract->name,
                'signer_email' => $email,
                'customer_name' => $contract->customer_name,
                'signature_uploaded' => true
            ]
            ]);

            $signedCount = ContractSignature::where('contract_id', $contract->id)
            ->whereIn('signer_email', $emails)
            ->where('status', 'signed')
            ->count();

            Log::debug('Signature Check:', [
            'expected_emails' => $emails,
            'signed_count' => $signedCount,
            'expected_count' => count($emails),
            ]);

            if ($signedCount === count($emails)) {
            Log::info("All signatures signed for contract {$contract->id}. Activating contract.");
            $contract->status = 'active';
            $contract->save();

            // ✅ Generate PDF and send email
            foreach ($emails as $recipient) {
                try {
                // Step 1: Prepare signatures (with base64 images)
                $signatures = ContractSignature::where('contract_id', $contract->id)->get()->map(function ($sig) {
                    if ($sig->signature_image_path) {
                    $path = storage_path('app/public/' . $sig->signature_image_path);
                    if (file_exists($path)) {
                        $imageData = file_get_contents($path);
                        $base64 = base64_encode($imageData);
                        $mime = mime_content_type($path); // e.g., 'image/png'
                        $sig->signature_base64 = 'data:' . $mime . ';base64,' . $base64;
                    } else {
                        $sig->signature_base64 = null;
                        \Log::warning("Signature image not found at: " . $path);
                    }
                    } else {
                    $sig->signature_base64 = null;
                    }
                    return $sig;
                });

                // Step 2: Generate the PDF once
                $pdfContent = PDF::loadView('pdf.contract-details', [
                    'contract' => $contract,
                    'signatures' => $signatures,
                ])->setPaper('a4')->output();

                // Step 3: Save the PDF file
                $pdfDirectory = 'contracts/' . date('Y/m/d');
                $slugContractName = Str::slug($contract->name);
                $pdfFilename = $slugContractName . '-' . Str::random(8) . '.pdf'; // add unique suffix
                $relativePath = "$pdfDirectory/$pdfFilename";
                $absolutePath = storage_path("app/$relativePath");

                Storage::disk('local')->makeDirectory($pdfDirectory);

                if (file_put_contents($absolutePath, $pdfContent) === false) {
                    throw new \Exception("Failed to write PDF to disk at $absolutePath");
                }

                Log::info("PDF generated and saved", [
                    'path' => $absolutePath,
                    'size' => strlen($pdfContent),
                ]);

                // Step 4: Send emails with the PDF attached
                foreach ($emails as $recipient) {
                    try {
                    $signer = ContractSignature::where('contract_id', $contract->id)
                        ->where('signer_email', $recipient)
                        ->first();
                    $recipientName = $signer?->signer_name ?? 'Valued Customer';

                    Mail::to($recipient)->queue(new FinalContractMail(
                        $recipientName,
                        $contract->name,
                        $relativePath
                    ));

                    Log::info("Queued email to {$recipient}");
                    } catch (\Throwable $e) {
                    Log::error("Error while sending final contract email to {$recipient}: " . $e->getMessage());
                    }
                }
                } catch (\Throwable $e) {
                Log::error("Error during PDF generation or emailing: " . $e->getMessage());
                }
            }
            }

            return response()->json([
            'success' => true,
            'message' => 'Signature submitted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Signature submission failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
