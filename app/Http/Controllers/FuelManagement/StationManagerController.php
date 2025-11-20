<?php

namespace App\Http\Controllers\FuelManagement;

use App\Http\Controllers\Controller;
use App\Models\FuelManagement\FuelStation;
use App\Models\FuelManagement\StationManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log, Session, Storage, Validator};
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StationManagerController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId) {
                return redirect()->route('auth.login')->with('error', 'Company session expired. Please login again.');
            }

            $managers = StationManager::with(['station'])
                ->forCompany($companyId)
                ->orderBy('full_name')
                ->get();

            $stations = FuelStation::forCompany($companyId)
                ->orderBy('name')
                ->get();

            return view('company.FuelManagement.stationmanager', [
                'managers' => $managers,
                'stations' => $stations,
                'lastSyncedAt' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('StationManagerController@index failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
            ]);

            return redirect()->route('auth.login')->with('error', 'Unable to load station managers at the moment.');
        }
    }

    public function stations(Request $request): JsonResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to continue',
                ], 401);
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired. Please login again.',
                ], 401);
            }

            $stations = FuelStation::forCompany($companyId)
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'location']);

            return response()->json([
                'success' => true,
                'data' => $stations,
            ]);
        } catch (\Exception $e) {
            Log::error('StationManagerController@stations failed', [
                'error' => $e->getMessage(),
                'session_id' => session()->getId(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch stations at the moment.',
            ], 500);
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
                return redirect()->route('auth.login')->with('error', 'Company session expired. Please login again.');
            }

            $avatarFile = $request->file('avatar');
            if ($avatarFile && !$avatarFile->isValid()) {
                $avatarError = method_exists($avatarFile, 'getErrorMessage') ? $avatarFile->getErrorMessage() : null;
                return redirect()->back()->withErrors([
                    'avatar' => $avatarError ?: 'Avatar upload failed. Please choose an image under 4MB and try again.',
                ])->withInput();
            }

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'gender' => ['required', 'string', Rule::in(['Female', 'Male', 'Other'])],
                'dob' => ['required', 'date', 'before:today'],
                'phone' => [
                    'required',
                    'string',
                    'max:30',
                    Rule::unique('station_managers', 'phone')->where(fn($query) => $query->where('company_id', $companyId)),
                ],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('station_managers', 'email')->where(fn($query) => $query->where('company_id', $companyId)),
                ],
                'station_id' => ['nullable', 'integer', Rule::exists('fuel_stations', 'id')->where(fn($query) => $query->where('company_id', $companyId))],
                'assign_date' => ['required', 'date'],
                'address' => ['required', 'string'],
                'location' => ['nullable', 'string', 'max:255'],
                'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $validated = $validator->validated();

            $station = null;
            if (!empty($validated['station_id'])) {
                $station = FuelStation::forCompany($companyId)->find($validated['station_id']);
            }

            $avatarPath = null;
            if ($avatarFile) {
                $avatarPath = $avatarFile->store('station_managers', 'public');
            }

            StationManager::create([
                'company_id' => $companyId,
                'fuel_station_id' => $station?->id,
                'full_name' => $validated['name'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['dob'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'avatar_path' => $avatarPath,
                'address' => $validated['address'],
                'location' => $validated['location'] ?? $station?->location,
                'assigned_at' => $validated['assign_date'],
                'status' => StationManager::STATUS_ACTIVE,
                'created_by' => $this->getAuthenticatedUserId(),
            ]);

            DB::commit();

            return redirect()->route('company.fuel.station-managers.index')->with('success', 'Station manager created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }

            Log::error('StationManagerController@store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
                'request' => $request->except(['avatar']),
            ]);

            return redirect()->back()->withInput()->with('error', 'Unable to create station manager. Please try again.');
        }
    }

    public function show(StationManager $stationManager): JsonResponse
    {
        try {
            $companyId = $this->resolveCompanyId();

            if (!$companyId || $stationManager->company_id !== $companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Manager not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $stationManager->load('station'),
            ]);
        } catch (\Exception $e) {
            Log::error('StationManagerController@show failed', [
                'error' => $e->getMessage(),
                'manager_id' => $stationManager->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch manager details.',
            ], 500);
        }
    }

    public function update(Request $request, StationManager $stationManager): RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId || $stationManager->company_id !== $companyId) {
                return redirect()->route('company.fuel.station-managers.index')->with('error', 'Station manager not found.');
            }

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'gender' => ['required', 'string', Rule::in(['Female', 'Male', 'Other'])],
                'dob' => ['required', 'date', 'before:today'],
                'phone' => [
                    'required',
                    'string',
                    'max:30',
                    Rule::unique('station_managers', 'phone')
                        ->ignore($stationManager->id)
                        ->where(fn($query) => $query->where('company_id', $companyId)),
                ],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('station_managers', 'email')
                        ->ignore($stationManager->id)
                        ->where(fn($query) => $query->where('company_id', $companyId)),
                ],
                'station_id' => ['nullable', 'integer', Rule::exists('fuel_stations', 'id')->where(fn($query) => $query->where('company_id', $companyId))],
                'assign_date' => ['required', 'date'],
                'address' => ['required', 'string'],
                'location' => ['nullable', 'string', 'max:255'],
                'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                'status' => ['nullable', 'string', Rule::in([
                    StationManager::STATUS_ACTIVE,
                    StationManager::STATUS_INACTIVE,
                    StationManager::STATUS_TERMINATED,
                ])],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $validated = $validator->validated();

            $station = null;
            if (!empty($validated['station_id'])) {
                $station = FuelStation::forCompany($companyId)->find($validated['station_id']);
            }

            $avatarPath = $stationManager->avatar_path;
            if ($request->hasFile('avatar')) {
                $newAvatarPath = $request->file('avatar')->store('station_managers', 'public');
                if ($avatarPath) {
                    Storage::disk('public')->delete($avatarPath);
                }
                $avatarPath = $newAvatarPath;
            }

            $stationManager->update([
                'fuel_station_id' => $station?->id,
                'full_name' => $validated['name'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['dob'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'avatar_path' => $avatarPath,
                'address' => $validated['address'],
                'location' => $validated['location'] ?? $station?->location,
                'assigned_at' => $validated['assign_date'],
                'status' => $validated['status'] ?? $stationManager->status,
                'updated_by' => $this->getAuthenticatedUserId(),
            ]);

            DB::commit();

            return redirect()->route('company.fuel.station-managers.index')->with('success', 'Station manager updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('StationManagerController@update failed', [
                'error' => $e->getMessage(),
                'manager_id' => $stationManager->id,
            ]);

            return redirect()->back()->withInput()->with('error', 'Unable to update station manager. Please try again.');
        }
    }

    public function destroy(StationManager $stationManager): RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId || $stationManager->company_id !== $companyId) {
                return redirect()->route('company.fuel.station-managers.index')->with('error', 'Station manager not found.');
            }

            $managerName = $stationManager->full_name;

            $stationManager->delete();

            return redirect()->route('company.fuel.station-managers.index')->with('success', "{$managerName} removed successfully.");
        } catch (\Exception $e) {
            Log::error('StationManagerController@destroy failed', [
                'error' => $e->getMessage(),
                'manager_id' => $stationManager->id,
            ]);

            return redirect()->route('company.fuel.station-managers.index')->with('error', 'Unable to delete station manager. Please try again.');
        }
    }

    public function terminate(Request $request, StationManager $stationManager): RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId || $stationManager->company_id !== $companyId) {
                return redirect()->route('company.fuel.station-managers.index')->with('error', 'Station manager not found.');
            }

            $validator = Validator::make($request->all(), [
                'terminate_reason' => ['required', 'string', 'max:1000'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $stationManager->update([
                'status' => StationManager::STATUS_TERMINATED,
                'termination_reason' => $validator->validated()['terminate_reason'],
                'terminated_at' => now(),
                'terminated_by' => $this->getAuthenticatedUserId(),
            ]);

            return redirect()->route('company.fuel.station-managers.index')->with('success', 'Station manager terminated successfully.');
        } catch (\Exception $e) {
            Log::error('StationManagerController@terminate failed', [
                'error' => $e->getMessage(),
                'manager_id' => $stationManager->id,
            ]);

            return redirect()->back()->with('error', 'Unable to terminate station manager. Please try again.');
        }
    }

    public function sendSms(Request $request, StationManager $stationManager): RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId || $stationManager->company_id !== $companyId) {
                return redirect()->route('company.fuel.station-managers.index')->with('error', 'Station manager not found.');
            }

            $validator = Validator::make($request->all(), [
                'message' => ['required', 'string', 'max:500'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $message = $validator->validated()['message'];

            // Placeholder for SMS integration
            Log::info('Station manager SMS queued', [
                'manager_id' => $stationManager->id,
                'phone' => $stationManager->phone,
                'message' => $message,
                'company_id' => $companyId,
            ]);

            return redirect()->route('company.fuel.station-managers.index')->with('success', 'SMS scheduled successfully.');
        } catch (\Exception $e) {
            Log::error('StationManagerController@sendSms failed', [
                'error' => $e->getMessage(),
                'manager_id' => $stationManager->id,
            ]);

            return redirect()->back()->with('error', 'Unable to send SMS at the moment. Please try again.');
        }
    }

    private function isAuthenticated(): bool
    {
        return Auth::guard('company_sub_user')->check() || Auth::guard('sub_user')->check() || Auth::check();
    }

    private function resolveCompanyId(): ?int
    {
        $companyId = Session::get('selected_company_id');

        if ($companyId) {
            return $companyId;
        }

        if (Auth::guard('company_sub_user')->check()) {
            $companyId = Auth::guard('company_sub_user')->user()->company_id;
        } elseif (Auth::guard('sub_user')->check()) {
            $companyId = Auth::guard('sub_user')->user()->company_id;
        } elseif (Auth::check()) {
            $user = Auth::user();

            if ($user->companyProfile) {
                $companyId = $user->companyProfile->id ?? $user->id;
            } else {
                $companyId = $user->id;
            }
        }

        if ($companyId) {
            Session::put('selected_company_id', $companyId);
            return $companyId;
        }

        if (config('app.env') === 'local' || config('app.debug')) {
            $fallbackId = 1;
            Session::put('selected_company_id', $fallbackId);
            return $fallbackId;
        }

        return null;
    }

    private function getAuthenticatedUserId(): ?int
    {
        if (Auth::guard('company_sub_user')->check()) {
            return Auth::guard('company_sub_user')->id();
        }

        if (Auth::guard('sub_user')->check()) {
            return Auth::guard('sub_user')->id();
        }

        return Auth::id();
    }
}
