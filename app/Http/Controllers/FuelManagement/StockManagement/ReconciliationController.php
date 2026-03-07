<?php

namespace App\Http\Controllers\FuelManagement\StockManagement;

use App\Http\Controllers\Controller;
use App\Models\FuelManagement\FuelStation;
use App\Models\FuelManagement\StockManagement\StockReconciliation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log, Session};
use Illuminate\View\View;

class ReconciliationController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        Log::info('ReconciliationController@index START');

        try {
            $companyId = $this->resolveCompanyId();

            if (!$companyId) {
                return redirect()->back()
                    ->with('error', 'Unable to determine company context');
            }

            $stationId = $request->input('station_id');
            $isManagerRestricted = false;
            $managerStationId = null;
            $managerStationName = null;

            if (Auth::guard('company_sub_user')->check()) {
                $subUser = Auth::guard('company_sub_user')->user();
                if ($subUser && $subUser->fuel_station_id) {
                    $managerStationId = (int) $subUser->fuel_station_id;
                    $stationId = $managerStationId;
                    $isManagerRestricted = true;
                    $managerStation = FuelStation::forCompany($companyId)->find($managerStationId);
                    $managerStationName = $managerStation ? $managerStation->name : null;
                }
            }

            $reconciliationsQuery = StockReconciliation::with(['station'])
                ->forCompany($companyId)
                ->orderByDate('desc');

            if ($stationId) {
                $reconciliationsQuery->forStation((int) $stationId);
            }

            $reconciliations = $reconciliationsQuery->get();

            // Simple summary values for the cards (can be refined later)
            $latest = $reconciliations->first();
            $summaryOpening = $latest?->opening_stock ?? 0;
            $summarySales = $reconciliations->sum('sales_volume');
            $summaryVariance = $reconciliations->sum('variance');

            $stationsQuery = FuelStation::forCompany($companyId)
                ->select('id', 'name', 'code', 'location')
                ->orderBy('name');

            if ($isManagerRestricted && $managerStationId) {
                $stationsQuery->where('id', $managerStationId);
            }

            $stations = $stationsQuery->get();

            $stationName = null;
            if ($stationId) {
                $currentStation = $stations->firstWhere('id', (int) $stationId)
                    ?? FuelStation::forCompany($companyId)->find($stationId);
                $stationName = $currentStation?->name;
            }

            if (!$stationName) {
                $stationName = $isManagerRestricted
                    ? ($managerStationName ?? 'Your Station')
                    : 'All Stations';
            }

            return view('company.FuelManagement.stockRecon', [
                'reconciliations' => $reconciliations,
                'stations' => $stations,
                'stationId' => $stationId ? (int) $stationId : null,
                'stationName' => $stationName,
                'companyId' => $companyId,
                'summaryOpening' => $summaryOpening,
                'summarySales' => $summarySales,
                'summaryVariance' => $summaryVariance,
                'isManagerRestricted' => $isManagerRestricted,
                'managerStationId' => $managerStationId,
                'managerStationName' => $managerStationName,
            ]);
        } catch (\Exception $e) {
            Log::error('ReconciliationController@index failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
            ]);

            return redirect()->back()->with('error', 'Unable to load reconciliation data at the moment.');
        }
    }

    private function isAuthenticated(): bool
    {
        return Auth::guard('company_sub_user')->check()
            || Auth::guard('sub_user')->check()
            || Auth::check();
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            if (!$this->isAuthenticatedStationManager()) {
                return redirect()->route('company.fuel.reconciliations.index')
                    ->with('error', 'Only station managers can record stock reconciliation.');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId) {
                return redirect()->route('auth.login')
                    ->with('error', 'Company session expired. Please login again.');
            }

            $subUser = Auth::guard('company_sub_user')->user();
            $stationId = $subUser?->fuel_station_id;

            if (!$stationId) {
                return redirect()->route('company.fuel.stations.index')
                    ->with('error', 'No station is assigned to your profile. Please contact your administrator.');
            }

            $validated = $request->validate([
                'recon_date' => ['required', 'date'],
                'tank' => ['required', 'string', 'max:100'],
                'opening_stock' => ['required', 'numeric', 'min:0'],
                'add_stock' => ['required', 'numeric', 'min:0'],
                'sales_volume' => ['required', 'numeric', 'min:0'],
                'closing_stock' => ['required', 'numeric', 'min:0'],
                'dipping_reading' => ['required', 'numeric', 'min:0'],
                'variance' => ['required', 'numeric'],
                'notes' => ['nullable', 'string'],
            ]);

            DB::beginTransaction();

            $totalStock = (float) $validated['opening_stock'] + (float) $validated['add_stock'];

            StockReconciliation::create([
                'company_id' => $companyId,
                'station_id' => (int) $stationId,
                'recon_date' => $validated['recon_date'],
                'tank' => $validated['tank'],
                'opening_stock' => $validated['opening_stock'],
                'add_stock' => $validated['add_stock'],
                'total_stock' => $totalStock,
                'sales_volume' => $validated['sales_volume'],
                'closing_stock' => $validated['closing_stock'],
                'dipping_reading' => $validated['dipping_reading'],
                'variance' => $validated['variance'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => $this->getAuthenticatedUserId(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Reconciliation entry recorded successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('ReconciliationController@store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
                'request' => $request->except(['_token']),
            ]);

            return redirect()->back()->withInput()->with('error', 'Unable to record reconciliation entry. Please try again.');
        }
    }

    public function destroy(StockReconciliation $reconciliation): RedirectResponse
    {
        try {
            if (!$this->isAuthenticatedStationManager()) {
                return redirect()->route('company.fuel.reconciliations.index')
                    ->with('error', 'Only station managers can manage reconciliation entries.');
            }

            $companyId = $this->resolveCompanyId();

            if (
                !$companyId ||
                $reconciliation->company_id !== $companyId
            ) {
                return redirect()->back()->with('error', 'Reconciliation entry not found.');
            }

            $subUser = Auth::guard('company_sub_user')->user();
            $stationId = $subUser?->fuel_station_id;

            if ((int) $reconciliation->station_id !== (int) $stationId) {
                return redirect()->back()->with('error', 'You are not allowed to delete entries for another station.');
            }

            DB::beginTransaction();

            $reconciliation->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Reconciliation entry deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('ReconciliationController@destroy failed', [
                'error' => $e->getMessage(),
                'reconciliation_id' => $reconciliation->id ?? null,
                'session_id' => session()->getId(),
            ]);

            return redirect()->back()->with('error', 'Unable to delete reconciliation entry. Please try again.');
        }
    }

    private function isAuthenticatedStationManager(): bool
    {
        if (!Auth::guard('company_sub_user')->check()) {
            return false;
        }

        $subUser = Auth::guard('company_sub_user')->user();

        return (bool) ($subUser && $subUser->fuel_station_id);
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

