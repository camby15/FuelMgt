<?php

namespace App\Http\Controllers\FuelManagement\StockManagement;

use App\Http\Controllers\Controller;
use App\Models\FuelManagement\FuelStation;
use App\Models\FuelManagement\StockManagement\Stock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log, Session};
use Illuminate\View\View;

class StockController extends Controller
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
                } elseif ($isSubUser) {
                    $subUser = Auth::guard('sub_user')->user();
                    $companyId = $subUser->company_id;
                    Session::put('selected_company_id', $companyId);
                } elseif ($isDefaultAuth) {
                    $user = Auth::user();
                    if ($user->companyProfile) {
                        $companyId = $user->companyProfile->id ?? $user->id;
                    } else {
                        $companyId = $user->id;
                    }
                    Session::put('selected_company_id', $companyId);
                }
            }

            if (!$companyId) {
                return redirect()->route('auth.login')->with('error', 'Unable to determine company context');
            }

            $stationId = $request->input('station_id');

            $stocksQuery = Stock::with(['station'])
                ->forCompany($companyId)
                ->orderByDate('desc');

            if ($stationId) {
                $stocksQuery->forStation($stationId);
            }

            $stocks = $stocksQuery->get();

            $stations = FuelStation::forCompany($companyId)
                ->with(['activeManager'])
                ->select('id', 'name', 'code', 'location')
                ->orderBy('name')
                ->get();

            $agoBalance = Stock::forCompany($companyId)
                ->forStation($stationId)
                ->forProduct(Stock::PRODUCT_AGO)
                ->orderByDate('desc')
                ->first();

            $pmsBalance = Stock::forCompany($companyId)
                ->forStation($stationId)
                ->forProduct(Stock::PRODUCT_PMS)
                ->orderByDate('desc')
                ->first();

            return view('company.FuelManagement.stock', [
                'stocks' => $stocks,
                'stations' => $stations,
                'stationId' => $stationId,
                'agoBalance' => $agoBalance ? $agoBalance->running_balance : 0,
                'pmsBalance' => $pmsBalance ? $pmsBalance->running_balance : 0,
                'companyId' => $companyId,
            ]);
        } catch (\Exception $e) {
            Log::error('StockController@index failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
            ]);

            return redirect()->back()->with('error', 'Unable to load stock data at the moment.');
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

            $validated = $request->validate([
                'delivery_date' => ['required', 'date'],
                'brv_number' => ['required', 'string', 'max:100'],
                'driver_name' => ['required', 'string', 'max:255'],
                'driver_phone' => ['required', 'string', 'max:30'],
                'invoice_number' => ['required', 'string', 'max:100'],
                'product_type' => ['required', 'in:AGO,PMS'],
                'dispatched_quantity' => ['required', 'numeric', 'min:0'],
                'received_quantity' => ['required', 'numeric', 'min:0'],
                'station_id' => ['required', 'integer', 'exists:fuel_stations,id'],
                'inspected_by' => ['required', 'string', 'max:255'],
            ]);

            DB::beginTransaction();

            $lastStock = Stock::forCompany($companyId)
                ->forStation($validated['station_id'])
                ->forProduct($validated['product_type'])
                ->orderByDate('desc')
                ->first();

            $previousBalance = $lastStock ? $lastStock->running_balance : 0;
            $runningBalance = $previousBalance + (float) $validated['received_quantity'];

            Stock::create([
                'company_id' => $companyId,
                'station_id' => $validated['station_id'],
                'delivery_date' => $validated['delivery_date'],
                'brv_number' => $validated['brv_number'],
                'driver_name' => $validated['driver_name'],
                'driver_phone' => $validated['driver_phone'],
                'invoice_number' => $validated['invoice_number'],
                'product_type' => $validated['product_type'],
                'dispatched_quantity' => $validated['dispatched_quantity'],
                'received_quantity' => $validated['received_quantity'],
                'inspected_by' => $validated['inspected_by'],
                'running_balance' => $runningBalance,
                'created_by' => $this->getAuthenticatedUserId(),
            ]);

            DB::commit();

            return redirect()->route('company.fuel.stocks.index')->with('success', 'Stock recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('StockController@store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
                'request' => $request->except(['_token']),
            ]);

            return redirect()->back()->withInput()->with('error', 'Unable to record stock. Please try again.');
        }
    }

    public function show(Stock $stock): View|RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId || $stock->company_id !== $companyId) {
                return redirect()->route('company.fuel.stocks.index')->with('error', 'Stock entry not found.');
            }

            $stock->load(['station', 'creator', 'updater']);

            return view('company.FuelManagement.stock-show', [
                'stock' => $stock,
            ]);
        } catch (\Exception $e) {
            Log::error('StockController@show failed', [
                'error' => $e->getMessage(),
                'stock_id' => $stock->id,
                'session_id' => session()->getId(),
            ]);

            return redirect()->route('company.fuel.stocks.index')->with('error', 'Unable to load stock details.');
        }
    }

    public function destroy(Stock $stock): RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId || $stock->company_id !== $companyId) {
                return redirect()->route('company.fuel.stocks.index')->with('error', 'Stock entry not found.');
            }

            DB::beginTransaction();

            $stock->delete();

            DB::commit();

            return redirect()->route('company.fuel.stocks.index')->with('success', 'Stock entry deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('StockController@destroy failed', [
                'error' => $e->getMessage(),
                'stock_id' => $stock->id,
                'session_id' => session()->getId(),
            ]);

            return redirect()->route('company.fuel.stocks.index')->with('error', 'Unable to delete stock entry. Please try again.');
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
