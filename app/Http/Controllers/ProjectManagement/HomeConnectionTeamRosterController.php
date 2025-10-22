<?php

namespace App\Http\Controllers\ProjectManagement;

use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\HomeConnectionTeamRoster;
use App\Models\ProjectManagement\SiteAssignment;
use App\Models\TeamParing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;

class HomeConnectionTeamRosterController extends Controller
{
    /**
     * Display a listing of team rosters
     */
    public function index(Request $request)
    {
        try {
            Log::info('HomeConnectionTeamRosterController@index called');
            
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()->route('auth.login')->with('error', 'Company session expired');
            }

            // Get filters
            $teamFilter = $request->get('team_id');
            $weekStart = $request->get('week_start');

            // Build query
            $query = HomeConnectionTeamRoster::where('company_id', $companyId)
                ->with(['team.teamMembers', 'siteAssignment.customer', 'creator', 'updater']);

            // Apply team filter
            if ($teamFilter && $teamFilter !== 'all') {
                $query->where('team_id', $teamFilter);
            }

            // Apply date filter
            if ($weekStart) {
                $weekEnd = date('Y-m-d', strtotime($weekStart . ' +6 days'));
                $query->whereBetween('schedule_date', [$weekStart, $weekEnd]);
            }

            // Get rosters with pagination
            $rosters = $query->orderBy('schedule_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // If AJAX request, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'rosters' => $rosters->items(),
                    'pagination' => [
                        'current_page' => $rosters->currentPage(),
                        'last_page' => $rosters->lastPage(),
                        'per_page' => $rosters->perPage(),
                        'total' => $rosters->total(),
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'rosters' => $rosters
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching rosters: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to fetch rosters'
                ], 500);
            }
            
            return back()->with('error', 'Unable to fetch rosters');
        }
    }

    /**
     * Store a newly created roster
     */
    public function store(Request $request)
    {
        try {
            Log::info('HomeConnectionTeamRosterController@store called', $request->all());

            if (!Auth::check()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()->route('auth.login')->with('error', 'Company session expired');
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                'team_id' => 'required|exists:team_paring,id',
                'site_assignment_id' => 'required|exists:site_assignments,id',
                'schedule_date' => 'required|date',
                'shift_type' => 'nullable|in:full_day,morning,afternoon,night',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i|after:start_time',
                'status' => 'nullable|in:Scheduled,Pending,In Progress,Completed,Cancelled',
                'notes' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please correct the errors below.');
            }

            // Create roster
            $roster = HomeConnectionTeamRoster::create([
                'team_id' => $request->team_id,
                'site_assignment_id' => $request->site_assignment_id,
                'company_id' => $companyId,
                'schedule_date' => $request->schedule_date,
                'shift_type' => $request->shift_type ?? 'full_day',
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => $request->status ?? 'Scheduled',
                'notes' => $request->notes,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            Log::info('Roster created successfully', ['roster_id' => $roster->id]);

            return redirect()->route('project-management.home-connection')
                ->with('success', 'Schedule created successfully');

        } catch (QueryException $e) {
            Log::error('Database error creating roster: ' . $e->getMessage());
            return back()->with('error', 'Unable to create schedule');
        } catch (\Exception $e) {
            Log::error('Error creating roster: ' . $e->getMessage());
            return back()->with('error', 'Unable to create schedule');
        }
    }

    /**
     * Display the specified roster
     */
    public function show($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $roster = HomeConnectionTeamRoster::where('id', $id)
                ->where('company_id', $companyId)
                ->with(['team.teamMembers', 'siteAssignment.customer', 'creator', 'updater'])
                ->firstOrFail();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'roster' => $roster
                ]);
            }

            return response()->json(['roster' => $roster]);

        } catch (\Exception $e) {
            Log::error('Error fetching roster details: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Roster not found'
                ], 404);
            }
            
            return back()->with('error', 'Roster not found');
        }
    }

    /**
     * Show the form for editing the specified roster
     */
    public function edit($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $roster = HomeConnectionTeamRoster::where('id', $id)
                ->where('company_id', $companyId)
                ->with(['team', 'siteAssignment'])
                ->firstOrFail();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'roster' => $roster
                ]);
            }

            return response()->json(['roster' => $roster]);

        } catch (\Exception $e) {
            Log::error('Error preparing roster edit: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Roster not found'
                ], 404);
            }
            
            return back()->with('error', 'Roster not found');
        }
    }

    /**
     * Update the specified roster
     */
    public function update(Request $request, $id)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()->route('auth.login')->with('error', 'Company session expired');
            }

            $roster = HomeConnectionTeamRoster::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            // Validate request
            $validatedData = $request->validate([
                'team_id' => 'required|exists:team_paring,id',
                'site_assignment_id' => 'required|exists:site_assignments,id',
                'schedule_date' => 'required|date',
                'shift_type' => 'nullable|in:full_day,morning,afternoon,night',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i|after:start_time',
                'status' => 'required|in:Scheduled,Pending,In Progress,Completed,Cancelled',
                'notes' => 'nullable|string|max:1000',
            ]);

            $validatedData['updated_by'] = Auth::id();

            $roster->update($validatedData);

            Log::info('Roster updated successfully', ['roster_id' => $id]);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Schedule updated successfully'
                ]);
            }

            return redirect()->route('project-management.home-connection')
                ->with('success', 'Schedule updated successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Error updating roster: ' . $e->getMessage());

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to update schedule'
                ], 500);
            }

            return back()->with('error', 'Unable to update schedule');
        }
    }

    /**
     * Remove the specified roster
     */
    public function destroy($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $roster = HomeConnectionTeamRoster::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            $roster->delete();
            
            Log::info('Roster deleted successfully', ['roster_id' => $id]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Schedule deleted successfully'
                ]);
            }

            return redirect()->route('project-management.home-connection')
                ->with('success', 'Schedule deleted successfully');

        } catch (\Exception $e) {
            Log::error('Error deleting roster: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to delete schedule'
                ], 500);
            }
            
            return back()->with('error', 'Unable to delete schedule');
        }
    }

    /**
     * Export rosters to CSV
     */
    public function export(Request $request)
    {
        try {
            if (!Auth::check()) {
                return redirect()->back()->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()->back()->with('error', 'Company session expired');
            }

            $query = HomeConnectionTeamRoster::where('company_id', $companyId)
                ->with(['team', 'siteAssignment.customer']);
            
            // Apply filters if provided
            if ($request->has('team_id') && $request->team_id !== 'all') {
                $query->where('team_id', $request->team_id);
            }

            if ($request->has('week_start')) {
                $weekEnd = date('Y-m-d', strtotime($request->week_start . ' +6 days'));
                $query->whereBetween('schedule_date', [$request->week_start, $weekEnd]);
            }
            
            $rosters = $query->orderBy('schedule_date', 'desc')->get();

            $filename = 'team_rosters_export_' . date('Y-m-d_H-i-s') . '.csv';
            $tempFile = tempnam(sys_get_temp_dir(), $filename);
            $handle = fopen($tempFile, 'w');

            // Write header row
            $headers = ['Team', 'Team Code', 'Project', 'Location', 'Schedule Date', 'Shift Type', 'Status', 'Notes', 'Created At'];
            fputcsv($handle, $headers);

            // Write roster data
            foreach ($rosters as $roster) {
                $row = [
                    $roster->team->team_name ?? 'N/A',
                    $roster->team->team_code ?? 'N/A',
                    $roster->siteAssignment->customer->customer_name ?? 'N/A',
                    $roster->siteAssignment->customer->location ?? 'N/A',
                    $roster->schedule_date->format('Y-m-d'),
                    ucfirst(str_replace('_', ' ', $roster->shift_type)),
                    $roster->status,
                    $roster->notes ?? '',
                    $roster->created_at->format('Y-m-d H:i:s')
                ];
                fputcsv($handle, $row);
            }

            fclose($handle);

            return response()->download($tempFile, $filename, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Error exporting rosters: ' . $e->getMessage());
            return back()->with('error', 'Unable to export rosters');
        }
    }
}
