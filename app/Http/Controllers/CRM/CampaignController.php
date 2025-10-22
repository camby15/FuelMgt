<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignDetail;
use App\Models\CompanySubUser;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class CampaignController extends Controller
{
    /**
     * Display a listing of campaigns.
     */
    public function index()
    {
        try {
            // Extensive logging for debugging
            Log::info('Campaign Index Method Called', [
                'authenticated' => Auth::check(),
                'user_id' => Auth::id(),
                'session_data' => Session::all()
            ]);

            if (!Auth::check()) {
                Log::warning('User not authenticated when accessing campaigns');
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            
            // Log company ID details
            Log::info('Company ID Check', [
                'company_id' => $companyId,
                'is_null' => is_null($companyId)
            ]);

            if (!$companyId) {
                Log::warning('No company ID found in session');
                return redirect()->route('auth.login')->with('error', 'Company session expired. Please login again.');
            }

            // Fetch campaigns with extensive logging
            $campaigns = Campaign::where('company_id', $companyId)
                ->where('deleted', 0) // Use where() instead of and()
                ->latest()
                ->paginate(10); // Paginate 10 per page

            // Log campaigns details
            Log::info('campaigns Fetch Result', [
                'count' => $campaigns->count(),
                'company_id' => $companyId,
                'first_Campaign' => $campaigns->first() ? $campaigns->first()->toArray() : null
            ]);

            // Always pass $campaigns, even if it's an empty collection
            return response()->json([
                'error' => false,
                'message' => 'campaigns fetched successfully',
                'data' => $campaigns->items(), // Leads data
                'pagination' => [
                    'total' => $campaigns->total(),
                    'per_page' => $campaigns->perPage(), 
                    'current_page' => $campaigns->currentPage(),
                    'last_page' => $campaigns->lastPage(),
                    'next_page_url' => $campaigns->nextPageUrl(),
                    'prev_page_url' => $campaigns->previousPageUrl()
                ]
            ]);
    
        } catch (\Exception $e) {
            // Comprehensive error logging
            Log::error('Error in Campaign Index Method', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'company_id' => Session::get('selected_company_id')
            ]);
            
            // Pass an empty collection to prevent undefined variable error
            return view('company.CRM.marketing', [
                'campaigns' => collect(),
                'activeTab' => 'customer'
            ]);
        }
    }

    public function filter(Request $request)
    {
        $campaigns = Campaign::query()
            ->with('campaignDetails')
            ->where('company_id', session('selected_company_id'));

        if ($request->filled('search')) {
            $campaigns->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $campaigns->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $campaigns->where('status', $request->status);
        }

        $campaigns = $campaigns->latest()->get();

        $html = view('company.CRM.marketing', compact('campaigns'))->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Show the form for creating a new campaign.
     */
    public function create()
    {
        return view('campaigns.create');
    }

    /**
     * Store a newly created campaign in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'required|numeric|min:0',
            'target_audience' => 'nullable|array', 
            'assign_users' => 'nullable|array',
            'campaign_goals' => 'nullable|string',
            'kpis_lead_goal' => 'nullable|numeric', 
            'kpis_conversion_goal' => 'nullable|numeric', 
            'kpis_roi_goal' => 'nullable|numeric', 
            'status' => 'required|string|in:active,inactive,draft',
        ]);

        $validated['company_id'] = Auth::user()->companyProfile->id ?? null;
        $validated['created_by'] = Auth::id();
        $validated['deleted'] = 0; // Not deleted by default

        try {
            // Create Campaign
            $campaign = Campaign::create($validated);

            // Create Campaign Detail Entry
            CampaignDetail::create([
                'campaign_id' => $campaign->id,
                'assigned_users'=> $validated['assign_users'] ?? [],
                'goals' => $validated['campaign_goals'] ?? null,
                'kpis' => json_encode([
                    'lead_goal' => $request->kpi_lead_goal,
                    'conversion_goal' => $request->kpi_conversion_goal,
                    'roi_goal' => $request->kpi_roi_goal,
                ]),
                'created_by' => Auth::id(),
            ]);

            Session::flash('success', 'Campaign created successfully!');
        } catch (\Exception $e) {
            Log::error('Campaign creation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage()); // Show real error
        }

        return redirect()->back();
    }

    /**
     * Show a specific campaign.
     */
    public function view(Campaign $campaign)
    {
        if ($campaign->deleted) {
            abort(404);
        }
    
        $campaign->load('campaignDetails'); // Eager load the details
    
        return view('company.CRM.view-campaign', [
            'campaign' => $campaign,
            'campaignDetails' => $campaign->campaignDetails,
        ]);
    }    

    public function edit(Campaign $campaign)
    {
        if ($campaign->deleted) {
            abort(404);
        }
        return view('campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        try {
            if ($campaign->deleted) {
                abort(404);
            }

            // Check if the request is primarily for notes/documents
            $isNoteOrDocumentUpdate = $request->has('notes') || $request->hasFile('documents');

            // Define base validation rules
            $rules = [
                'notes' => 'nullable|array',
                'documents' => 'nullable|array',
                'documents.*' => 'file|mimes:pdf,doc,docx,jpg,png,jpeg',
            ];
            // Only enforce campaign field validations if it's not just a note/document update
            if (!$isNoteOrDocumentUpdate) {
                $rules = array_merge($rules, [
                    'name' => 'required|string|max:255',
                    'type' => 'required|string',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                    'budget' => 'required|numeric|min:0',
                    'target_audience' => 'nullable|array',
                    'assign_users' => 'nullable|array',
                    'campaign_goals' => 'nullable|string',
                    'kpi_lead_goal' => 'nullable|numeric',
                    'kpi_conversion_goal' => 'nullable|numeric',
                    'kpi_roi_goal' => 'nullable|numeric',
                    'status' => 'required|string|in:draft,active,paused,completed,cancelled',
                ]);
            }
            
            // Validate dynamically
            $validated = $request->validate($rules);
            $validated['edited_by'] = Auth::id();

            DB::beginTransaction();

            // Only update campaign fields if it's a full update
            if (!$isNoteOrDocumentUpdate) {
                $campaign->update($validated);
            }

            // Retrieve existing campaign details
            $campaignDetails = $campaign->campaignDetails()->firstOrCreate([
                'campaign_id' => $campaign->id
            ]);

            // Merge new notes with existing notes
            $existingNotes = json_decode($campaignDetails->notes, true) ?? [];
            $newNotes = $validated['notes'] ?? [];
            $mergedNotes = array_merge($existingNotes, $newNotes);

            // Handle document uploads
            $existingDocuments = json_decode($campaignDetails->documents, true) ?? [];
            $newDocuments = [];

            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    $path = $document->store('campaign_documents', 'public'); // Store in `storage/app/public/campaign_documents`
                    $newDocuments[] = $path;
                }
            }

            // Merge old and new document paths
            $mergedDocuments = array_merge($existingDocuments, $newDocuments);

            // Update campaign details
            $campaignDetails->update([
                'goals' => $validated['campaign_goals'] ?? $campaignDetails->goals,
                'assigned_users'=> $validated['assign_users'] ?? [],
                'notes' => json_encode($mergedNotes),
                'documents' => json_encode($mergedDocuments),
                'kpis' => json_encode([
                    'lead_goal' => $validated['kpi_lead_goal'] ?? null,
                    'conversion_goal' => $validated['kpi_conversion_goal'] ?? null,
                    'roi_goal' => $validated['kpi_roi_goal'] ?? null,
                ]),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Campaign updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating campaign: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function storeNotes(Request $request, $campaignId)
    {
        // Check if both notes and documents are empty
        $hasNotes = $request->has('notes') && count(array_filter($request->notes ?? [])) > 0;
        $hasDocuments = $request->hasFile('documents') && count($request->file('documents')) > 0;
        
        // If both are empty, return without processing
        if (!$hasNotes && !$hasDocuments) {
            return redirect()->back()->with('info', 'No notes or documents were provided.');
        }
        
        // Validate the request
        $data = $request->validate([
            'notes.*' => 'nullable|string',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max size
        ]);
        // $campaign = Campaign::findOrFail($campaignId);
        
        // Get existing campaign details
        $campaignDetails = CampaignDetail::firstOrCreate(['campaign_id' => $campaignId]);
        $details = json_decode($campaignDetails->documents, true) ?? [];
        $notes = json_decode($campaignDetails->notes, true) ?? [];

        // Initialize notes and documents if not present
        if (!isset($notes['notes'])) {
            $notes['notes'] = [];
        }
        if (!isset($details['documents'])) {
            $details['documents'] = [];
        }
        
        // Handle notes if present
        if ($request->has('notes')) {
            foreach ($request->notes as $note) {
                if (!empty($note)) {
                    $notes['notes'][] = [
                        'content' => $note,
                        'added_at' => now()->toDateTimeString(),
                        'added_by' => auth()->id()
                    ];
                }
            }
            $campaignDetails->notes = json_encode($notes);
        }
        
        // Handle file uploads with simplified approach
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('campaign-documents', 'public');
                    
                    $details['documents'][] = [
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'added_at' => now()->toDateTimeString(),
                        'added_by' => auth()->id()
                    ];
                }
            }
            $campaignDetails->documents = json_encode($details);
        }
        
        // Save updated details
        $campaignDetails->save();
        
        return redirect()->back()->with('success', 'Notes and documents added successfully.');
    }

    public function export(Request $request)
    {
        try {
            $format = $request->input('format', 'csv');

            $campaigns = Campaign::all();
            // dd($campaigns);
            $exportData = [];

            // Headers
            $exportData[] = [
                'Campaign',
                'Type',
                'Status',
                'Start Date',
                'End Date',
                'Budget',
                'Created By',
                'Created At',
                'Assigned Users',
                'Goals',
                'Lead Goal',
                'Conversion Goal',
                'ROI Goal',
                'Notes',
                'Documents'
            ];

            foreach ($campaigns as $campaign) {
                $details = $campaign->campaignDetails;
            
                $created_by = User::find($campaign->created_by);
            
                // Assigned Users → convert IDs to names
                $assignedUserNames = '';
                if (!empty($details->assigned_users) && is_array($details->assigned_users)) {
                    $userNames = CompanySubUser::whereIn('id', $details->assigned_users)->pluck('fullname')->toArray();
                    $assignedUserNames = implode(', ', $userNames);
                }
            
                // KPIs
                $kpis = json_decode(optional($details)->kpis, true) ?? [];
                $leadGoal = $kpis['lead_goal'] ?? '';
                $conversionGoal = $kpis['conversion_goal'] ?? '';
                $roiGoal = $kpis['roi_goal'] ?? '';
            
                // Notes as newline list
                $noteContents = '';
                $notesData = json_decode($campaign->campaignDetails->notes, true);
                $notes = $notesData['notes'] ?? [];
                if (is_array($notes)) {
                    $noteContents = implode("\n", array_column($notes, 'content'));
                }
            
                // Documents as newline list
                $documentNames = '';
                $documentsData = json_decode($campaign->campaignDetails->documents, true);
                $documents = $documentsData['documents'] ?? [];
                if (is_array($documents)) {
                    $documentNames = implode("\n", array_column($documents, 'original_name'));
                }
            
                $exportData[] = [
                    $campaign->name,
                    ucfirst($campaign->type),
                    ucfirst($campaign->status),
                    $campaign->start_date ? $campaign->start_date->format('M d, Y') : 'N/A',
                    $campaign->end_date ? $campaign->end_date->format('M d, Y') : 'N/A',
                    number_format($campaign->budget, 2),
                    $created_by->fullname ?? 'Unknown',
                    $campaign->created_at->format('M d, Y H:i'),
                    $assignedUserNames,
                    $details->goals ?? '',
                    $leadGoal,
                    $conversionGoal,
                    $roiGoal,
                    $noteContents,
                    $documentNames
                ];
            }
            
            // CSV export
            if ($format === 'csv') {
                $output = fopen('php://temp', 'w');
                foreach ($exportData as $row) {
                    fputcsv($output, $row);
                }
                rewind($output);
                $csv = stream_get_contents($output);
                fclose($output);

                return Response::make($csv)
                    ->header('Content-Type', 'text/csv; charset=UTF-8')
                    ->header('Content-Disposition', 'attachment; filename="campaigns.csv"');
            }

            return response()->json([
                'success' => false,
                'message' => 'Unsupported export format'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Error exporting campaigns', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to export campaigns'
            ], 500);
        }
    }

    public function destroy(Campaign $campaign)
    {
        try {
            if ($campaign->deleted) {
                abort(404);
            }

            // Ensure campaign exists before attempting update
            if (!$campaign) {
                return redirect()->back()->with('error', 'Campaign not found.');
            }

            $updated = $campaign->update([
                'deleted' => 1,
                'deleted_at' => now(),
                'deleted_by' => Auth::id(),
            ]);

            if (!$updated) {
                return redirect()->back()->with('error', 'Campaign deletion failed.');
            }

            return redirect()->back()->with('success', 'Campaign deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting campaign: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete the campaign. Please try again.');
        }
    }

}
