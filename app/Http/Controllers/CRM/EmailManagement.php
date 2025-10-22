<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Mail\ComposeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\SentEmail;
use App\Models\DeletedEmail;
use App\Models\Company;
use App\Models\EmailDraft;

class EmailManagement extends Controller
{
    public function sendEmail(Request $request)
    {
        try {
            // Validate incoming request data
            $request->validate([
                'recipient' => ['required', 'email:rfc,dns', 'string'],
                'subject' => ['required', 'string', 'max:255'],
                'message' => ['required', 'string'],
            ], [
                'recipient.email' => 'Please enter a valid email address (e.g., example@domain.com)',
                'recipient.required' => 'Email recipient is required',
                'subject.required' => 'Email subject is required',
                'subject.max' => 'Subject cannot be longer than 255 characters',
                'message.required' => 'Email message is required'
            ]);

            // Check if the user is authenticated
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Get the company name from the authenticated user's company profile
            $user = Auth::user();
            $companyProfile = $user->companyProfile;
            $companyName = $companyProfile ? $companyProfile->company_name : 'Company';

            // Log the attempt to send the email
            Log::info('Attempting to send email', [
                'recipient' => $request->recipient,
                'subject' => $request->subject,
                'company_name' => $companyName
            ]);

            $message = (string) $request->message;

            try {
                // Queue the email
                Mail::to($request->recipient)->queue(new ComposeEmail($request->recipient, $request->subject, $message, $companyName));

                // Save the sent email
                SentEmail::create([
                    'company_id' => Session::get('selected_company_id'),
                    'sent_by_user_id' => Auth::id(),
                    'recipient' => $request->recipient,
                    'subject' => $request->subject,
                    'message' => $message,
                    'status' => 'sent'
                ]);

                // Log success
                Log::info('Email queued successfully', [
                    'recipient' => $request->recipient,
                    'subject' => $request->subject
                ]);

                return response()->json(['success' => true, 'message' => 'Email sent successfully!']);
            } catch (\Exception $e) {
                // Save the failed email attempt
                SentEmail::create([
                    'company_id' => Session::get('selected_company_id'),
                    'sent_by_user_id' => Auth::id(),
                    'recipient' => $request->recipient,
                    'subject' => $request->subject,
                    'message' => $message,
                    'status' => 'failed'
                ]);

                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error sending email', [
                'error' => $e->getMessage(),
                'recipient' => $request->recipient ?? null,
                'subject' => $request->subject ?? null
            ]);

            return response()->json(['error' => 'Failed to send email: ' . $e->getMessage()], 500);
        }
    }

    public function getSentEmails()
    {
        $companyId = Session::get('selected_company_id');
        $sentEmails = SentEmail::where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->get();

        $html = view('company.CRM.sent-emails-list', ['sentEmails' => $sentEmails])->render();
        return response()->json(['html' => $html]);
    }

    public function getDeletedEmails()
    {
        $companyId = Session::get('selected_company_id');
        $deletedEmails = DeletedEmail::where('company_id', $companyId)
            ->orderBy('deleted_at', 'desc')
            ->get();

        $html = view('company.CRM.deleted-emails-list', ['deletedEmails' => $deletedEmails])->render();
        return response()->json(['html' => $html]);
    }

    public function deleteEmail(Request $request)
    {
        try {
            $request->validate([
                'email_id' => 'required'
            ]);

            // Check if email exists in sent_emails table
            $email = SentEmail::find($request->email_id);
            
            if ($email) {
                // Create record in deleted_emails table
                DeletedEmail::create([
                    'company_id' => $email->company_id,
                    'sent_by_user_id' => $email->sent_by_user_id,
                    'recipient' => $email->recipient,
                    'subject' => $email->subject,
                    'message' => $email->message,
                    'attachment' => $email->attachment,
                    'sent_at' => $email->created_at
                ]);

                // Delete from sent_emails
                $email->delete();

                // Get updated sent emails list
                $sentEmails = SentEmail::where('company_id', session('selected_company_id'))
                    ->orderBy('created_at', 'desc')
                    ->get();
                $html = view('company.CRM.sent-emails-list', ['sentEmails' => $sentEmails])->render();
            } else {
                // If not in sent_emails, check deleted_emails
                $deletedEmail = DeletedEmail::findOrFail($request->email_id);
                $deletedEmail->delete(); // Permanently delete

                // Get updated deleted emails list
                $deletedEmails = DeletedEmail::where('company_id', session('selected_company_id'))
                    ->orderBy('created_at', 'desc')
                    ->get();
                $html = view('company.CRM.deleted-emails-list', ['deletedEmails' => $deletedEmails])->render();
            }

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting email', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Could not delete the email.'], 500);
        }
    }

    public function restoreEmail(Request $request)
    {
        try {
            $request->validate([
                'email_id' => 'required|exists:deleted_emails,id'
            ]);

            $deletedEmail = DeletedEmail::findOrFail($request->email_id);
            
            // Restore to sent_emails
            SentEmail::create([
                'company_id' => $deletedEmail->company_id,
                'sent_by_user_id' => $deletedEmail->sent_by_user_id,
                'recipient' => $deletedEmail->recipient,
                'subject' => $deletedEmail->subject,
                'message' => $deletedEmail->message,
                'status' => 'sent'
            ]);

            // Delete from deleted_emails
            $deletedEmail->delete();

            return response()->json([
                'success' => true,
                'message' => 'Email restored successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error restoring email', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Failed to restore email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveDraft(Request $request)
    {
        try {
            $request->validate([
                'recipient' => ['required', 'email:rfc,dns', 'string'],
                'subject' => ['required', 'string', 'max:255'],
                'message' => ['required', 'string'],
            ]);

            $draft = EmailDraft::create([
                'company_id' => Session::get('selected_company_id'),
                'user_id' => Auth::id(),
                'recipient' => $request->recipient,
                'subject' => $request->subject,
                'message' => $request->message
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email saved as draft successfully!',
                'draft' => $draft
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving draft email', [
                'error' => $e->getMessage(),
                'recipient' => $request->recipient ?? null,
                'subject' => $request->subject ?? null
            ]);

            return response()->json([
                'error' => 'Failed to save draft: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDraftEmails()
    {
        $companyId = Session::get('selected_company_id');
        $drafts = EmailDraft::where('company_id', $companyId)
            ->orderBy('updated_at', 'desc')
            ->get();

        $html = view('company.CRM.draft-emails-list', ['draftEmails' => $drafts])->render();
        return response()->json(['html' => $html]);
    }

    public function getDraft($id)
    {
        try {
            $draft = EmailDraft::where('id', $id)
                ->where('company_id', Session::get('selected_company_id'))
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'draft' => $draft
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Draft not found'
            ], 404);
        }
    }

    public function deleteDraft($id)
    {
        try {
            $draft = EmailDraft::where('id', $id)
                ->where('company_id', Session::get('selected_company_id'))
                ->firstOrFail();

            $draft->delete();

            return response()->json([
                'success' => true,
                'message' => 'Draft deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete draft'
            ], 500);
        }
    }
}
