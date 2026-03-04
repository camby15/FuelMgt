<?php

namespace App\Http\Controllers\FuelManagement;

use App\Http\Controllers\Controller;
use App\Models\FuelManagement\FuelAttendant;
use App\Models\FuelManagement\FuelStation;
use App\Models\FuelManagement\Roster;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log, Session};
use Illuminate\View\View;

class RosterController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        try {
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isDefaultAuth = Auth::check();
            $isSubUser = Auth::guard('sub_user')->check();

            if (!$isCompanySubUser && !$isDefaultAuth && !$isSubUser) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');

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
                } else {
                    $user = Auth::user();
                    $companyId = $user->company_id ?? null;
                    if ($companyId) {
                        Session::put('selected_company_id', $companyId);
                        Log::info('Set company ID from default auth', ['company_id' => $companyId]);
                    }
                }
            }

            if (!$companyId) {
                return redirect()->route('auth.login')->with('error', 'Unable to determine company context');
            }

            // Get week start date from request or default to current ISO week Monday
            $weekStartDate = $request->input('week_start_date', now()->startOfWeek()->format('Y-m-d'));

            // Get station from request or use all stations for company
            $stationId = $request->input('station_id');

            // Get attendants for the company (filtered by station if selected)
            $attendantsQuery = FuelAttendant::with(['station'])
                ->forCompany($companyId)
                ->where('status', 'active');

            if ($stationId) {
                $attendantsQuery->where('fuel_station_id', $stationId);
            }

            $attendants = $attendantsQuery
                ->orderBy('first_name')
                ->orderBy('other_names')
                ->get();

            // Get stations for the company
            $stations = FuelStation::forCompany($companyId)
                ->select('id', 'name', 'code', 'location')
                ->orderBy('name')
                ->get();

            // Get roster entries for the week
            $rostersQuery = Roster::with(['attendant', 'station'])
                ->forCompany($companyId)
                ->forWeek($weekStartDate);

            if ($stationId) {
                $rostersQuery->forStation($stationId);
            }

            $rosters = $rostersQuery->get();

            // Group rosters by attendant for the view
            $rostersByAttendant = $rosters->groupBy('attendant_id');

            // Calculate stats
            $totalAttendants = $attendants->count();
            $scheduledAttendants = $rostersByAttendant->count();
            $coverageScore = $totalAttendants > 0 ? round(($scheduledAttendants / $totalAttendants) * 100) : 0;

            // Get manager's station if available
            $managerStation = null;
            if ($isCompanySubUser) {
                $subUser = Auth::guard('company_sub_user')->user();
                if ($subUser && $subUser->fuel_station_id) {
                    $managerStation = FuelStation::find($subUser->fuel_station_id);
                }
            }

            return view('company.FuelManagement.roasterManagement', [
                'attendants' => $attendants,
                'stations' => $stations,
                'rosters' => $rosters,
                'rostersByAttendant' => $rostersByAttendant,
                'weekStartDate' => $weekStartDate,
                'stationId' => $stationId,
                'managerStation' => $managerStation,
                'totalAttendants' => $totalAttendants,
                'scheduledAttendants' => $scheduledAttendants,
                'coverageScore' => $coverageScore,
                'company' => null,
                'companyId' => $companyId,
            ]);
        } catch (\Exception $e) {
            Log::error('RosterController@index failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
            ]);

            return redirect()->back()->with('error', 'Unable to load roster at the moment.');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId) {
                return redirect()->back()->with('error', 'Unable to determine company context');
            }

            $validated = $request->validate([
                'attendant_id' => ['required', 'integer', 'exists:fuel_attendants,id'],
                'station_id' => ['required', 'integer', 'exists:fuel_stations,id'],
                'week_start_date' => ['required', 'date'],
                'day_of_week' => ['required', 'integer', 'min:1', 'max:7'],
                'shift_type' => ['required', 'in:morning,evening,off'],
                'status' => ['nullable', 'in:draft,published'],
            ]);

            $validated['company_id'] = $companyId;
            $validated['status'] = $validated['status'] ?? 'draft';
            $validated['created_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();

            Roster::create($validated);

            return redirect()->back()->with('success', 'Roster entry created successfully.');
        } catch (\Exception $e) {
            Log::error('RosterController@store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Unable to create roster entry. Please try again.');
        }
    }

    public function update(Request $request, Roster $roster): RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId || $roster->company_id !== $companyId) {
                return redirect()->back()->with('error', 'Roster entry not found.');
            }

            $validated = $request->validate([
                'shift_type' => ['required', 'in:morning,evening,off'],
                'status' => ['nullable', 'in:draft,published'],
            ]);

            $validated['updated_by'] = Auth::id();

            $roster->update($validated);

            return redirect()->back()->with('success', 'Roster entry updated successfully.');
        } catch (\Exception $e) {
            Log::error('RosterController@update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Unable to update roster entry. Please try again.');
        }
    }

    public function destroy(Roster $roster): RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId || $roster->company_id !== $companyId) {
                return redirect()->back()->with('error', 'Roster entry not found.');
            }

            $roster->delete();

            return redirect()->back()->with('success', 'Roster entry deleted successfully.');
        } catch (\Exception $e) {
            Log::error('RosterController@destroy failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Unable to delete roster entry. Please try again.');
        }
    }

    private function isAuthenticated(): bool
    {
        return Auth::guard('company_sub_user')->check() ||
               Auth::guard('sub_user')->check() ||
               Auth::check();
    }

    private function resolveCompanyId(): ?int
    {
        $companyId = Session::get('selected_company_id');

        if (!$companyId) {
            if (Auth::guard('company_sub_user')->check()) {
                $subUser = Auth::guard('company_sub_user')->user();
                $companyId = $subUser->company_id;
            } elseif (Auth::guard('sub_user')->check()) {
                $subUser = Auth::guard('sub_user')->user();
                $companyId = $subUser->company_id;
            } elseif (Auth::check()) {
                $user = Auth::user();
                $companyId = $user->company_id ?? null;
            }
        }

        return $companyId;
    }

    public function autoAssign(Request $request): RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId) {
                return redirect()->back()->with('error', 'Unable to determine company context');
            }

            $validated = $request->validate([
                'week_start_date' => ['required', 'date'],
                'station_id' => ['nullable', 'integer', 'exists:fuel_stations,id'],
                'pattern' => ['nullable', 'in:balanced,frontload,evening,custom'],
            ]);

            $weekStartDate = $validated['week_start_date'];
            $stationId = $validated['station_id'] ?? null;
            $pattern = $validated['pattern'] ?? 'balanced';

            // Get active attendants for the company
            $attendantsQuery = FuelAttendant::forCompany($companyId)
                ->where('status', 'active');

            if ($stationId) {
                $attendantsQuery->where('fuel_station_id', $stationId);
            }

            $attendants = $attendantsQuery->get();

            if ($attendants->isEmpty()) {
                return redirect()->back()->with('error', 'No active attendants found for auto-assignment.');
            }

            DB::beginTransaction();

            try {
                // Delete existing roster entries for this week
                $deleteQuery = Roster::forCompany($companyId)->forWeek($weekStartDate);
                if ($stationId) {
                    $deleteQuery->forStation($stationId);
                }
                $deleteQuery->delete();

                // Create roster entries for each attendant for the week
                foreach ($attendants as $attendant) {
                    $assignments = $this->generateShiftPattern($pattern);

                    for ($day = 1; $day <= 7; $day++) {
                        Roster::create([
                            'company_id' => $companyId,
                            'station_id' => $attendant->fuel_station_id,
                            'attendant_id' => $attendant->id,
                            'week_start_date' => $weekStartDate,
                            'day_of_week' => $day,
                            'shift_type' => $assignments[$day - 1],
                            'status' => 'draft',
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ]);
                    }
                }

                DB::commit();

                return redirect()->back()->with('success', 'Roster auto-assigned successfully for ' . $attendants->count() . ' attendants.');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('RosterController@autoAssign failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Unable to auto-assign roster. Please try again.');
        }
    }

    private function generateShiftPattern(string $pattern): array
    {
        $patterns = [
            'balanced' => ['morning', 'evening', 'morning', 'evening', 'off', 'morning', 'evening'],
            'frontload' => ['morning', 'morning', 'morning', 'evening', 'off', 'evening', 'morning'],
            'evening' => ['evening', 'evening', 'morning', 'evening', 'off', 'morning', 'morning'],
            'custom' => ['morning', 'morning', 'off', 'evening', 'evening', 'morning', 'evening'],
        ];

        return $patterns[$pattern] ?? $patterns['balanced'];
    }

    public function swapShift(Request $request): RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId) {
                return redirect()->back()->with('error', 'Unable to determine company context');
            }

            $validated = $request->validate([
                'roster_id' => ['required', 'integer', 'exists:rosters,id'],
                'new_shift_type' => ['required', 'in:morning,evening,off'],
            ]);

            $roster = Roster::findOrFail($validated['roster_id']);

            if ($roster->company_id !== $companyId) {
                return redirect()->back()->with('error', 'Roster entry not found.');
            }

            $roster->update([
                'shift_type' => $validated['new_shift_type'],
                'updated_by' => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'Shift swapped successfully.');
        } catch (\Exception $e) {
            Log::error('RosterController@swapShift failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Unable to swap shift. Please try again.');
        }
    }

    public function assignOffDay(Request $request): RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId) {
                return redirect()->back()->with('error', 'Unable to determine company context');
            }

            $validated = $request->validate([
                'roster_id' => ['required', 'integer', 'exists:rosters,id'],
            ]);

            $roster = Roster::findOrFail($validated['roster_id']);

            if ($roster->company_id !== $companyId) {
                return redirect()->back()->with('error', 'Roster entry not found.');
            }

            $roster->update([
                'shift_type' => 'off',
                'updated_by' => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'Off day assigned successfully.');
        } catch (\Exception $e) {
            Log::error('RosterController@assignOffDay failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Unable to assign off day. Please try again.');
        }
    }
}
