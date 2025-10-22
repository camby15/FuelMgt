<?php

namespace App\Http\Controllers\Newsletters;

use App\Http\Controllers\Controller;
use App\Models\CompanyNewsLetter;
use Illuminate\Support\Facades\{Auth, Session, Log, Validator};
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\View\View;
use Carbon\Carbon;

class CompanyNewsLetterController extends Controller
{
    /**
     * Display a listing of newsletter templates and campaigns.
     */
    public function index(): View
    {
        try {
            // Validate user authentication
            if (!Auth::check()) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Please login to continue');
            }

            // Get the current company ID from session
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                Log::warning('No company ID in session');
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Company session expired. Please login again.');
            }

            // Debug logging
            Log::info('Fetching newsletters for company', [
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);

            // Fetch newsletter templates and campaigns for the current company
            $templates = CompanyNewsLetter::where('company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->get();

            // Additional debug logging
            Log::info('Newsletter query results', [
                'count' => $templates->count(),
                'templates' => $templates->toArray()
            ]);

            // Return view with newsletters
            return view('company.Digital-marketing.newsletter-templates', compact('templates'));

        } catch (\Exception $e) {
            Log::error('Error fetching newsletter templates', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Error fetching newsletter templates: ' . $e->getMessage());
        }
    }

    /**
     * Store a new newsletter template.
     */
    public function store(Request $request)
    {
        try {
            // Validate user authentication
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to continue'
                ], 401);
            }

            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            // Validate request data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'content' => 'required|string',
                'template_status' => 'required|in:active,draft'
            ]);

            // Create new newsletter template
            $newsletter = new CompanyNewsLetter();
            $newsletter->company_id = $companyId;
            $newsletter->name = $validated['name'];
            $newsletter->subject = $validated['subject'];
            $newsletter->content = $validated['content'];
            $newsletter->template_status = $validated['template_status'];
            
            if ($newsletter->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Newsletter template created successfully',
                    'data' => $newsletter
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to create newsletter template'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error creating newsletter template', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error creating newsletter template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing a newsletter template.
     */
    public function edit(CompanyNewsLetter $newsletterTemplate): View
    {
        try {
            // Validate user authentication
            if (!Auth::check()) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Unauthorized access');
            }
    
            // Validate company ownership
            if ($newsletterTemplate->company_id !== Session::get('selected_company_id')) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Unauthorized access');
            }
    
            // Fetch all templates
            $templates = CompanyNewsLetter::where('company_id', Session::get('selected_company_id'))->get();
    
            // Return to the same view with the template to edit
            return view('company.Digital-marketing.newsletter-templates', [
                'templates' => $templates,
                'editTemplate' => $newsletterTemplate
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error loading newsletter template for editing', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return redirect()
                ->route('company-newsletters.index')
                ->with('error', 'Unable to load newsletter template for editing');
        }
    }

    /**
     * Update an existing newsletter template.
     */
    public function update(Request $request, CompanyNewsLetter $companyNewsLetter)
    {
        try {
            // Validate user authentication and company ownership
            if (!Auth::check() || $companyNewsLetter->company_id !== Session::get('selected_company_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 401);
            }

            // Validate request data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'content' => 'required|string',
                'template_status' => 'required|in:active,draft'
            ]);

            // Update template
            $companyNewsLetter->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Newsletter template updated successfully',
                'data' => $companyNewsLetter->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating newsletter template', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating newsletter template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Prepare and schedule a newsletter campaign.
     */
    public function scheduleCampaign(Request $request, CompanyNewsLetter $companyNewsLetter): RedirectResponse
    {
        try {
            // Validate user authentication and company ownership
            if (!Auth::check() || $companyNewsLetter->company_id !== Session::get('selected_company_id')) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Unauthorized access');
            }

            // Validate request data
            $validated = $request->validate([
                'recipients' => 'required|array',
                'recipients.*' => 'email',
                'send_date' => 'required|date|after:now'
            ]);

            // Prepare recipients and set campaign details
            $companyNewsLetter->prepareRecipients($validated['recipients'])
                ->fill([
                    'send_date' => Carbon::parse($validated['send_date']),
                    'campaign_status' => 'pending'
                ])
                ->save();

            return redirect()
                ->route('company-newsletters.index')
                ->with('success', 'Newsletter campaign scheduled successfully');

        } catch (\Exception $e) {
            Log::error('Error scheduling newsletter campaign', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error scheduling newsletter campaign: ' . $e->getMessage());
        }
    }

    /**
     * View newsletter campaign performance.
     */
    public function viewPerformance(CompanyNewsLetter $companyNewsLetter): View
    {
        try {
            // Validate user authentication and company ownership
            if (!Auth::check() || $companyNewsLetter->company_id !== Session::get('selected_company_id')) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Unauthorized access');
            }

            // Get performance summary
            $performanceSummary = $companyNewsLetter->getPerformanceSummary();

            return view('newsletters.company.digital-marketing.newsletter-performance', [
                'newsletter' => $companyNewsLetter,
                'performance' => $performanceSummary
            ]);

        } catch (\Exception $e) {
            Log::error('Error viewing newsletter performance', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Error retrieving newsletter performance: ' . $e->getMessage());
        }
    }

    /**
     * Delete a newsletter template.
     */
    public function destroy(CompanyNewsLetter $companyNewsLetter)
    {
        try {
            // Validate user authentication and company ownership
            if (!Auth::check() || $companyNewsLetter->company_id !== Session::get('selected_company_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 401);
            }

            // Delete the template
            $companyNewsLetter->delete();

            return response()->json([
                'success' => true,
                'message' => 'Newsletter template deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting newsletter template', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error deleting newsletter template: ' . $e->getMessage()
            ], 500);
        }
    }
}