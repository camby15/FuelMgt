<?php

namespace App\Http\Controllers\FuelManagement;

use App\Http\Controllers\Controller;
use App\Models\FuelManagement\FuelStation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log, Session, Validator};
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FuelStationController extends Controller
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

            $stations = FuelStation::forCompany($companyId)
                ->orderBy('name')
                ->get();

            $managerNames = FuelStation::forCompany($companyId)
                ->whereNotNull('manager_name')
                ->distinct()
                ->orderBy('manager_name')
                ->pluck('manager_name');

            return view('company.FuelManagement.allstations', [
                'stations' => $stations,
                'managerNames' => $managerNames,
                'lastSyncedAt' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('FuelStationController@index failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
            ]);

            return redirect()->route('auth.login')->with('error', 'Unable to load stations at the moment.');
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

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => [
                    'required',
                    'string',
                    'max:64',
                    Rule::unique('fuel_stations', 'code')->where(fn($query) => $query->where('company_id', $companyId)),
                ],
                'products' => ['required', 'array', 'min:1'],
                'products.*' => ['required', Rule::in(['AGO', 'PMS'])],
                'location' => 'required|string|max:255',
                'gps_coordinates' => 'nullable|string|max:100',
                'manager' => 'required|string|max:255',
                'phone' => 'required|string|max:30',
                'address' => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $validated = $validator->validated();

            $products = collect($validated['products'])
                ->filter()
                ->map(fn($product) => strtoupper($product))
                ->unique()
                ->values()
                ->all();

            if (empty($products)) {
                return redirect()->back()
                    ->withErrors(['products' => 'Select at least one valid product.'])
                    ->withInput();
            }

            $primaryProduct = $products[0];

            FuelStation::create([
                'company_id' => $companyId,
                'name' => $validated['name'],
                'code' => $validated['code'],
                'product' => $primaryProduct,
                'products' => $products,
                'location' => $validated['location'],
                'gps_coordinates' => $validated['gps_coordinates'] ?? null,
                'manager_name' => $validated['manager'],
                'manager_phone' => $validated['phone'],
                'address' => $validated['address'],
                'status' => 'active',
                'created_by' => $this->getAuthenticatedUserId(),
            ]);

            DB::commit();

            return redirect()->route('company.fuel.stations.index')->with('success', 'Fuel station created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('FuelStationController@store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Unable to create station. Please try again.');
        }
    }

    public function show(FuelStation $station): JsonResponse
    {
        try {
            $companyId = $this->resolveCompanyId();

            if (!$companyId || $station->company_id !== $companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Station not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $station,
            ]);
        } catch (\Exception $e) {
            Log::error('FuelStationController@show failed', [
                'error' => $e->getMessage(),
                'station_id' => $station->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch station details.',
            ], 500);
        }
    }

    public function update(Request $request, FuelStation $station): RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId || $station->company_id !== $companyId) {
                return redirect()->route('company.fuel.stations.index')->with('error', 'Station not found.');
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => [
                    'required',
                    'string',
                    'max:64',
                    Rule::unique('fuel_stations', 'code')
                        ->ignore($station->id)
                        ->where(fn($query) => $query->where('company_id', $companyId)),
                ],
                'products' => ['required', 'array', 'min:1'],
                'products.*' => ['required', Rule::in(['AGO', 'PMS'])],
                'location' => 'required|string|max:255',
                'gps_coordinates' => 'nullable|string|max:100',
                'manager' => 'required|string|max:255',
                'phone' => 'required|string|max:30',
                'address' => 'required|string',
                'status' => 'nullable|string|max:32',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $validated = $validator->validated();

            $products = collect($validated['products'])
                ->filter()
                ->map(fn($product) => strtoupper($product))
                ->unique()
                ->values()
                ->all();

            if (empty($products)) {
                return redirect()->back()
                    ->withErrors(['products' => 'Select at least one valid product.'])
                    ->withInput();
            }

            $primaryProduct = $products[0];

            $station->update([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'product' => $primaryProduct,
                'products' => $products,
                'location' => $validated['location'],
                'gps_coordinates' => $validated['gps_coordinates'] ?? null,
                'manager_name' => $validated['manager'],
                'manager_phone' => $validated['phone'],
                'address' => $validated['address'],
                'status' => $validated['status'] ?? $station->status,
                'updated_by' => $this->getAuthenticatedUserId(),
            ]);

            DB::commit();

            return redirect()->route('company.fuel.stations.index')->with('success', 'Fuel station updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('FuelStationController@update failed', [
                'error' => $e->getMessage(),
                'station_id' => $station->id,
            ]);

            return redirect()->back()->withInput()->with('error', 'Unable to update station. Please try again.');
        }
    }

    public function destroy(FuelStation $station): RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId || $station->company_id !== $companyId) {
                return redirect()->route('company.fuel.stations.index')->with('error', 'Station not found.');
            }

            $stationName = $station->name;

            $station->delete();

            return redirect()->route('company.fuel.stations.index')->with('success', "{$stationName} deleted successfully.");
        } catch (\Exception $e) {
            Log::error('FuelStationController@destroy failed', [
                'error' => $e->getMessage(),
                'station_id' => $station->id,
            ]);

            return redirect()->route('company.fuel.stations.index')->with('error', 'Unable to delete station. Please try again.');
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
