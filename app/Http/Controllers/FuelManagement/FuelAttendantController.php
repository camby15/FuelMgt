<?php

namespace App\Http\Controllers\FuelManagement;

use App\Http\Controllers\Controller;
use App\Models\FuelManagement\FuelAttendant;
use App\Models\FuelManagement\FuelStation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log, Session, Storage, Validator};
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FuelAttendantController extends Controller
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
                } elseif ($isDefaultAuth) {
                    $user = Auth::user();
                    if ($user->companyProfile) {
                        $companyId = $user->companyProfile->id ?? $user->id;
                    } else {
                        $companyId = $user->id;
                    }

                    Session::put('selected_company_id', $companyId);
                    Log::info('Set company ID from default auth user', ['company_id' => $companyId]);
                }
            }

            if (!$companyId) {
                Log::warning('No company ID in session for fuel attendants');

                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Company session expired. Please login again.');
            }

            if ($isDefaultAuth) {
                $companyProfile = Auth::user()->companyProfile;
            } elseif ($isCompanySubUser) {
                $companyProfile = null;
            } elseif ($isSubUser) {
                $companyProfile = null;
            } else {
                $companyProfile = null;
            }

            $attendants = FuelAttendant::with(['station'])
                ->forCompany($companyId)
                ->join('fuel_stations', 'fuel_attendants.fuel_station_id', '=', 'fuel_stations.id')
                ->select([
                    'fuel_attendants.*',
                    'fuel_stations.name as station_name',
                    'fuel_stations.code as station_code'
                ])
                ->orderBy('fuel_attendants.first_name')
                ->orderBy('fuel_attendants.other_names')
                ->get();

            $stations = FuelStation::forCompany($companyId)
                ->select('id', 'name', 'code', 'location')
                ->orderBy('name')
                ->get();

            $activeAttendantsCount = $attendants->where('status', FuelAttendant::STATUS_ACTIVE)->count();
            $totalAttendantsCount = $attendants->count();
            $compliancePercentage = $totalAttendantsCount > 0
                ? round(($activeAttendantsCount / $totalAttendantsCount) * 100)
                : 0;

            return view('company.FuelManagement.attendants', [
                'attendants' => $attendants,
                'stations' => $stations,
                'company' => $companyProfile,
                'companyId' => $companyId,
                'lastSyncedAt' => now(),
                'activeAttendantsCount' => $activeAttendantsCount,
                'totalAttendantsCount' => $totalAttendantsCount,
                'compliancePercentage' => $compliancePercentage,
            ]);
        } catch (\Exception $e) {
            Log::error('FuelAttendantController@index failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
            ]);

            return redirect()->back()->with('error', 'Unable to load fuel attendants at the moment.');
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

            $photoFile = $request->file('profile_photo');
            if ($photoFile && !$photoFile->isValid()) {
                $errorMessage = method_exists($photoFile, 'getErrorMessage') ? $photoFile->getErrorMessage() : 'Unable to upload profile image.';

                return redirect()->back()->withErrors([
                    'profile_photo' => $errorMessage,
                ])->withInput();
            }

            $validator = Validator::make($request->all(), [
                'fuel_station_id' => [
                    'nullable',
                    'integer',
                    Rule::exists('fuel_stations', 'id')->where(fn($query) => $query->where('company_id', $companyId)),
                ],
                'staff_id' => [
                    'required',
                    'string',
                    'max:64',
                    Rule::unique('fuel_attendants', 'staff_id')->where(fn($query) => $query->where('company_id', $companyId)),
                ],
                'first_name' => ['required', 'string', 'max:120'],
                'other_names' => ['nullable', 'string', 'max:180'],
                'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
                'date_of_birth' => ['nullable', 'date', 'before:today'],
                'address' => ['required', 'string'],
                'phone_number_1' => [
                    'required',
                    'string',
                    'max:30',
                    Rule::unique('fuel_attendants', 'phone_number_1')->where(fn($query) => $query->where('company_id', $companyId)),
                ],
                'phone_number_2' => ['nullable', 'string', 'max:30'],
                'contact_name' => ['nullable', 'string', 'max:255'],
                'contact_relationship' => ['nullable', 'string', 'max:120'],
                'contact_phone' => ['nullable', 'string', 'max:30'],
                'contact_address' => ['nullable', 'string'],
                'status' => ['nullable', 'string', 'max:32'],
                'shift' => ['nullable', 'string', 'max:120'],
                'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $validated = $validator->validated();

            $photoPath = null;
            if ($photoFile) {
                $photoPath = $photoFile->store('fuel_attendants', 'public');
            }

            FuelAttendant::create([
                'company_id' => $companyId,
                'fuel_station_id' => $validated['fuel_station_id'] ?? null,
                'staff_id' => $validated['staff_id'],
                'first_name' => $validated['first_name'],
                'other_names' => $validated['other_names'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'address' => $validated['address'],
                'phone_number_1' => $validated['phone_number_1'],
                'phone_number_2' => $validated['phone_number_2'] ?? null,
                'contact_name' => $validated['contact_name'] ?? null,
                'contact_relationship' => $validated['contact_relationship'] ?? null,
                'contact_phone' => $validated['contact_phone'] ?? null,
                'contact_address' => $validated['contact_address'] ?? null,
                'status' => $validated['status'] ?? FuelAttendant::STATUS_ACTIVE,
                'shift' => $validated['shift'] ?? null,
                'profile_photo_path' => $photoPath,
                'created_by' => $this->getAuthenticatedUserId(),
            ]);

            DB::commit();

            return redirect()->route('company.fuel.attendants.index')->with('success', 'Fuel attendant created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            Log::error('FuelAttendantController@store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
                'request' => $request->except(['profile_photo']),
            ]);

            return redirect()->back()->withInput()->with('error', 'Unable to create fuel attendant. Please try again.');
        }
    }

    public function update(Request $request, FuelAttendant $attendant): RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId || $attendant->company_id !== $companyId) {
                return redirect()->route('company.fuel.attendants.index')->with('error', 'Fuel attendant not found.');
            }

            $photoFile = $request->file('profile_photo');
            if ($photoFile && !$photoFile->isValid()) {
                $errorMessage = method_exists($photoFile, 'getErrorMessage') ? $photoFile->getErrorMessage() : 'Unable to upload profile image.';

                return redirect()->back()->withErrors([
                    'profile_photo' => $errorMessage,
                ])->withInput();
            }

            $validator = Validator::make($request->all(), [
                'fuel_station_id' => [
                    'nullable',
                    'integer',
                    Rule::exists('fuel_stations', 'id')->where(fn($query) => $query->where('company_id', $companyId)),
                ],
                'staff_id' => [
                    'required',
                    'string',
                    'max:64',
                    Rule::unique('fuel_attendants', 'staff_id')
                        ->ignore($attendant->id)
                        ->where(fn($query) => $query->where('company_id', $companyId)),
                ],
                'first_name' => ['required', 'string', 'max:120'],
                'other_names' => ['nullable', 'string', 'max:180'],
                'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
                'date_of_birth' => ['nullable', 'date', 'before:today'],
                'address' => ['required', 'string'],
                'phone_number_1' => [
                    'required',
                    'string',
                    'max:30',
                    Rule::unique('fuel_attendants', 'phone_number_1')
                        ->ignore($attendant->id)
                        ->where(fn($query) => $query->where('company_id', $companyId)),
                ],
                'phone_number_2' => ['nullable', 'string', 'max:30'],
                'contact_name' => ['nullable', 'string', 'max:255'],
                'contact_relationship' => ['nullable', 'string', 'max:120'],
                'contact_phone' => ['nullable', 'string', 'max:30'],
                'contact_address' => ['nullable', 'string'],
                'status' => ['nullable', 'string', 'max:32'],
                'shift' => ['nullable', 'string', 'max:120'],
                'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $validated = $validator->validated();

            $photoPath = $attendant->profile_photo_path;
            if ($photoFile) {
                if ($photoPath) {
                    Storage::disk('public')->delete($photoPath);
                }

                $photoPath = $photoFile->store('fuel_attendants', 'public');
            }

            $attendant->update([
                'fuel_station_id' => $validated['fuel_station_id'] ?? null,
                'staff_id' => $validated['staff_id'],
                'first_name' => $validated['first_name'],
                'other_names' => $validated['other_names'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'address' => $validated['address'],
                'phone_number_1' => $validated['phone_number_1'],
                'phone_number_2' => $validated['phone_number_2'] ?? null,
                'contact_name' => $validated['contact_name'] ?? null,
                'contact_relationship' => $validated['contact_relationship'] ?? null,
                'contact_phone' => $validated['contact_phone'] ?? null,
                'contact_address' => $validated['contact_address'] ?? null,
                'status' => $validated['status'] ?? $attendant->status,
                'shift' => $validated['shift'] ?? $attendant->shift,
                'profile_photo_path' => $photoPath,
                'updated_by' => $this->getAuthenticatedUserId(),
            ]);

            DB::commit();

            return redirect()->route('company.fuel.attendants.index')->with('success', 'Fuel attendant updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('FuelAttendantController@update failed', [
                'error' => $e->getMessage(),
                'attendant_id' => $attendant->id,
                'session_id' => session()->getId(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Unable to update fuel attendant. Please try again.');
        }
    }

    public function destroy(FuelAttendant $attendant): RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId || $attendant->company_id !== $companyId) {
                return redirect()->route('company.fuel.attendants.index')->with('error', 'Fuel attendant not found.');
            }

            $photoPath = $attendant->profile_photo_path;

            $attendantName = $attendant->full_name;

            $attendant->delete();

            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            return redirect()->route('company.fuel.attendants.index')->with('success', "{$attendantName} deleted successfully.");
        } catch (\Exception $e) {
            Log::error('FuelAttendantController@destroy failed', [
                'error' => $e->getMessage(),
                'attendant_id' => $attendant->id,
                'session_id' => session()->getId(),
            ]);

            return redirect()->route('company.fuel.attendants.index')->with('error', 'Unable to delete fuel attendant. Please try again.');
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
