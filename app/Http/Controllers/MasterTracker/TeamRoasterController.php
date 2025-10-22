<?php

namespace App\Http\Controllers\MasterTracker;

use App\Http\Controllers\Controller;
use App\Models\TeamRoster;
use App\Models\TeamParing;
use App\Http\Requests\TeamRoster\StoreTeamRosterRequest;
use App\Http\Requests\TeamRoster\UpdateTeamRosterRequest;
use Illuminate\Support\Facades\{Log, Auth, Session, DB, Schema, Route};
use Illuminate\Http\{JsonResponse, Request, Response, RedirectResponse};
use Illuminate\View\View;

class TeamRoasterController extends Controller
{
    /**
     * Display a listing of the team rosters.
     */
    public function index(): View|RedirectResponse
    {
        try {
            // Check authentication with multiple guards
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isDefaultAuth = Auth::check();
            $isSubUser = Auth::guard('sub_user')->check();
            
            if (!$isCompanySubUser && !$isDefaultAuth && !$isSubUser) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            
            // If no company ID in session, try to set it from authenticated user
            if (!$companyId) {
                if ($isCompanySubUser) {
                    $subUser = Auth::guard('company_sub_user')->user();
                    $companyId = $subUser->company_id;
                    Session::put('selected_company_id', $companyId);
                    Log::info('Set company ID from company_sub_user', ['company_id' => $companyId]);
                } elseif ($isSubUser) {
                    $subUser = Auth::guard('sub_user')->user();
                    $companyId = $subUser->company_id;
                    Session::put('selected_company_id', $companyId);
                    Log::info('Set company ID from sub_user', ['company_id' => $companyId]);
                } elseif ($isDefaultAuth) {
                    $user = Auth::user();
                    if ($user->companyProfile) {
                        $companyId = $user->id;
                        Session::put('selected_company_id', $companyId);
                        Log::info('Set company ID from default auth user', ['company_id' => $companyId]);
                    }
                }
            }
            
            if (!$companyId) {
                Log::warning('No company ID in session');
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Company session expired. Please login again.');
            }

            // Get rosters for the company
            $rosters = TeamRoster::forCompany($companyId)
                ->with(['team', 'creator', 'updater'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Get teams for dropdown
            $teams = TeamParing::forCompany($companyId)
                ->orderBy('team_name')
                ->get();

            return view('company.MasterTracker.team-roaster', compact('rosters', 'teams'));

        } catch (\Exception $e) {
            Log::error('Error loading team rosters: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load team rosters.');
        }
    }

    /**
     * Store a newly created team roster.
     */
    public function store(StoreTeamRosterRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $roster = TeamRoster::create($request->validated());

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Team roster created successfully!',
                'data' => $roster->load(['team', 'creator'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating team roster: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create team roster.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified team roster.
     */
    public function show(TeamRoster $teamRoster): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if ($teamRoster->company_id !== $companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access to roster.'
                ], 403);
            }

            $teamRoster->load(['team', 'creator', 'updater']);

            return response()->json([
                'status' => 'success',
                'data' => $teamRoster
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading team roster: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load team roster.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified team roster.
     */
    public function edit(TeamRoster $teamRoster): View|RedirectResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            Log::info('Edit method called for roster ID: ' . $teamRoster->id);
            Log::info('Company ID from session: ' . $companyId);
            Log::info('Roster company ID: ' . $teamRoster->company_id);
            
            if ($teamRoster->company_id !== $companyId) {
                Log::warning('Unauthorized access attempt to roster: ' . $teamRoster->id);
                return redirect()->back()->with('error', 'Unauthorized access to roster.');
            }

            // Get teams for dropdown
            $teams = TeamParing::forCompany($companyId)
                ->orderBy('team_name')
                ->get();

            Log::info('Teams found: ' . $teams->count());

            // Get rosters for the table
            $rosters = TeamRoster::forCompany($companyId)
                ->with(['team', 'creator', 'updater'])
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Rosters found: ' . $rosters->count());
            Log::info('Roster being edited: ' . $teamRoster->roster_name);

            return view('company.MasterTracker.team-roaster', compact('rosters', 'teams', 'teamRoster'));

        } catch (\Exception $e) {
            Log::error('Error loading roster for editing: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to load roster for editing.');
        }
    }

    /**
     * Update the specified team roster.
     */
    public function update(UpdateTeamRosterRequest $request, TeamRoster $teamRoster): JsonResponse|RedirectResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if ($teamRoster->company_id !== $companyId) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unauthorized access to roster.'
                    ], 403);
                }
                return redirect()->back()->with('error', 'Unauthorized access to roster.');
            }

            DB::beginTransaction();

            $teamRoster->update($request->validated());

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Team roster updated successfully!',
                    'data' => $teamRoster->load(['team', 'creator', 'updater'])
                ]);
            }

            return redirect()->back()->with('success', 'Team roster updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating team roster: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update team roster.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update team roster.');
        }
    }

    /**
     * Remove the specified team roster.
     */
    public function destroy(Request $request, TeamRoster $teamRoster): JsonResponse|RedirectResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if ($teamRoster->company_id !== $companyId) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unauthorized access to roster.'
                    ], 403);
                }
                return redirect()->back()->with('error', 'Unauthorized access to roster.');
            }

            DB::beginTransaction();

            $teamRoster->delete();

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Team roster deleted successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Team roster deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting team roster: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to delete team roster.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to delete team roster.');
        }
    }

    /**
     * Get teams for dropdown selection.
     */
    public function getTeams(): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Company session expired.'
                ], 401);
            }

            $teams = TeamParing::forCompany($companyId)
                ->orderBy('team_name')
                ->get(['id', 'team_name', 'team_code', 'team_location', 'team_status']);

            return response()->json([
                'status' => 'success',
                'data' => $teams,
                'count' => $teams->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading teams: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load teams.'
            ], 500);
        }
    }

    /**
     * Get roster statistics.
     */
    public function getStats(): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Company session expired.'
                ], 401);
            }

            $stats = [
                'total_rosters' => TeamRoster::forCompany($companyId)->count(),
                'active_rosters' => TeamRoster::forCompany($companyId)->active()->count(),
                'draft_rosters' => TeamRoster::forCompany($companyId)->draft()->count(),
                'inactive_rosters' => TeamRoster::forCompany($companyId)->inactive()->count(),
                'weekly_rosters' => TeamRoster::forCompany($companyId)->byPeriod('weekly')->count(),
                'monthly_rosters' => TeamRoster::forCompany($companyId)->byPeriod('monthly')->count(),
            ];

            return response()->json([
                'status' => 'success',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading roster stats: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load roster statistics.'
            ], 500);
        }
    }

    /**
     * Get calendar events for FullCalendar.
     */
    public function getCalendarEvents(Request $request): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Company session expired.'
                ], 401);
            }

            $start = $request->get('start');
            $end = $request->get('end');

            $rosters = TeamRoster::forCompany($companyId)
                ->with(['team'])
                ->inDateRange($start, $end)
                ->get();

            $events = $rosters->map(function ($roster) {
                $color = match($roster->roster_status) {
                    'active' => '#28a745',
                    'draft' => '#ffc107',
                    'inactive' => '#6c757d',
                    default => '#007bff'
                };

                return [
                    'id' => $roster->id,
                    'title' => $roster->roster_name,
                    'start' => $roster->start_date->format('Y-m-d'),
                    'end' => $roster->end_date->addDay()->format('Y-m-d'),
                    'color' => $color,
                    'extendedProps' => [
                        'team_name' => $roster->team->team_name ?? 'Unknown Team',
                        'team_code' => $roster->team->team_code ?? '',
                        'roster_period' => $roster->roster_period,
                        'roster_status' => $roster->roster_status,
                        'working_days_count' => $roster->working_days_count,
                        'leave_days_count' => $roster->leave_days_count,
                    ]
                ];
            });

            return response()->json($events);

        } catch (\Exception $e) {
            Log::error('Error loading calendar events: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load calendar events.'
            ], 500);
        }
    }

    /**
     * Update roster status.
     */
    public function updateStatus(Request $request, TeamRoster $teamRoster): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if ($teamRoster->company_id !== $companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access to roster.'
                ], 403);
            }

            $request->validate([
                'status' => 'required|in:draft,active,inactive'
            ]);

            DB::beginTransaction();

            $teamRoster->update([
                'roster_status' => $request->status,
                'updated_by' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Roster status updated successfully!',
                'data' => $teamRoster
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating roster status: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update roster status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug environment for troubleshooting live vs local issues
     */
    public function debugEnvironment()
    {
        $debug = [
            'environment' => app()->environment(),
            'app_debug' => config('app.debug'),
            'database_connection' => config('database.default'),
            'session_driver' => config('session.driver'),
            'cache_driver' => config('cache.default'),
            'auth_guard' => config('auth.defaults.guard'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'server_name' => $_SERVER['SERVER_NAME'] ?? 'Unknown',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
            'file_permissions' => [
                'app_directory' => substr(sprintf('%o', fileperms(app_path())), -4),
                'storage_directory' => substr(sprintf('%o', fileperms(storage_path())), -4),
                'bootstrap_cache' => substr(sprintf('%o', fileperms(bootstrap_path('cache'))), -4),
            ],
            'database_status' => $this->checkDatabaseConnection(),
            'session_status' => $this->checkSessionStatus(),
            'team_roster_table_exists' => $this->checkTeamRosterTable(),
            'team_paring_table_exists' => $this->checkTeamParingTable(),
            'routes_registered' => $this->checkRoutes(),
        ];

        return response()->json($debug);
    }

    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return 'Connected';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    private function checkSessionStatus()
    {
        try {
            $sessionId = session()->getId();
            return $sessionId ? 'Active (ID: ' . substr($sessionId, 0, 8) . '...)' : 'Not Active';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    private function checkTeamRosterTable()
    {
        try {
            $exists = \Schema::hasTable('team_rosters');
            $count = $exists ? DB::table('team_rosters')->count() : 0;
            return $exists ? "Exists ({$count} records)" : 'Does not exist';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    private function checkTeamParingTable()
    {
        try {
            $exists = \Schema::hasTable('team_paring');
            $count = $exists ? DB::table('team_paring')->count() : 0;
            return $exists ? "Exists ({$count} records)" : 'Does not exist';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    private function checkRoutes()
    {
        try {
            $routes = \Route::getRoutes();
            $teamRosterRoutes = collect($routes)->filter(function ($route) {
                return str_contains($route->uri(), 'team-roaster');
            })->count();
            return "Found {$teamRosterRoutes} team-roaster routes";
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
