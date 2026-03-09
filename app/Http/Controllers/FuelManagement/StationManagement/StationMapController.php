<?php

namespace App\Http\Controllers\FuelManagement\StationManagement;

use App\Http\Controllers\Controller;
use App\Models\FuelManagement\FuelStation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class StationMapController extends Controller
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

            $allStations = FuelStation::forCompany($companyId)
                ->with(['activeManager'])
                ->orderBy('name')
                ->get();

            $stationsForMap = $allStations
                ->filter(function (FuelStation $station) {
                    return $station->latitude !== null && $station->longitude !== null;
                })
                ->map(function (FuelStation $station) {
                    $manager = $station->activeManager;

                    return [
                        'id' => $station->id,
                        'name' => $station->name,
                        'code' => $station->code,
                        'location' => $station->location,
                        'address' => $station->address,
                        'manager' => $manager?->full_name ?? 'Unassigned',
                        'phone' => $manager?->phone ?? '',
                        'lat' => $station->latitude,
                        'lng' => $station->longitude,
                    ];
                })
                ->values();

            return view('company.FuelManagement.station-map', [
                'stations' => $stationsForMap,
                'allStations' => $allStations,
                'lastSyncedAt' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('StationMapController@index failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
            ]);

            return redirect()->route('auth.login')->with('error', 'Unable to load station map at the moment.');
        }
    }

    private function isAuthenticated(): bool
    {
        return Auth::guard('company_sub_user')->check()
            || Auth::guard('sub_user')->check()
            || Auth::check();
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

