<?php

namespace App\Http\Controllers\FuelManagement\StockManagement;

use App\Http\Controllers\Controller;
use App\Models\FuelManagement\FuelStation;
use App\Models\FuelManagement\StockManagement\StockDispatch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\{Auth, DB, Log, Session, Storage};
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DispatchController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();

            if (!$companyId) {
                return redirect()->route('auth.login')->with('error', 'Unable to determine company context');
            }

            $stationId = $request->input('station_id');

            $dispatchesQuery = StockDispatch::with(['station'])
                ->forCompany($companyId)
                ->orderByDate('desc');

            if ($stationId) {
                $dispatchesQuery->forStation($stationId);
            }

            $dispatches = $dispatchesQuery->get();

            $stations = FuelStation::forCompany($companyId)
                ->select('id', 'name', 'code', 'location')
                ->orderBy('name')
                ->get();

            return view('company.FuelManagement.DispatchStock', [
                'dispatches' => $dispatches,
                'stations' => $stations,
                'stationId' => $stationId,
                'companyId' => $companyId,
            ]);
        } catch (\Exception $e) {
            Log::error('DispatchController@index failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
            ]);

            return redirect()->back()->with('error', 'Unable to load dispatch data at the moment.');
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
                'dispatch_date' => ['required', 'date'],
                'product_type' => ['required', 'in:AGO,PMS'],
                'depot' => ['required', 'string', 'max:255'],
                'bdc' => ['required', 'string', 'max:255'],
                'quantity_dispatched' => ['required', 'numeric', 'min:0'],
                'brv_number' => ['required', 'string', 'max:100'],
                'driver_name' => ['required', 'string', 'max:255'],
                'driver_phone' => ['nullable', 'string', 'max:30'],
                'station_id' => [
                    'required',
                    'integer',
                    function ($attribute, $value, $fail) use ($companyId) {
                        $exists = FuelStation::forCompany($companyId)->where('id', $value)->exists();
                        if (!$exists) {
                            $fail('The selected receiving station is invalid.');
                        }
                    },
                ],
                'inspected_by' => ['required', 'string', 'max:255'],
                'invoice_number' => ['required', 'string', 'max:100'],
                'waybill' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            ]);

            DB::beginTransaction();

            $waybillPath = null;
            if ($request->hasFile('waybill') && $request->file('waybill')->isValid()) {
                $waybillPath = $request->file('waybill')->store('stock_dispatches', 'public');
            }

            StockDispatch::create([
                'company_id' => $companyId,
                'station_id' => $validated['station_id'],
                'dispatch_date' => $validated['dispatch_date'],
                'product_type' => $validated['product_type'],
                'depot' => $validated['depot'],
                'bdc' => $validated['bdc'],
                'quantity_dispatched' => $validated['quantity_dispatched'],
                'brv_number' => $validated['brv_number'],
                'driver_name' => $validated['driver_name'],
                'driver_phone' => $validated['driver_phone'] ?? null,
                'inspected_by' => $validated['inspected_by'],
                'invoice_number' => $validated['invoice_number'],
                'waybill_path' => $waybillPath,
                'created_by' => $this->getAuthenticatedUserId(),
            ]);

            DB::commit();

            return redirect()->route('company.fuel.dispatches.index')->with('success', 'Dispatch recorded successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($waybillPath)) {
                Storage::disk('public')->delete($waybillPath);
            }

            Log::error('DispatchController@store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
                'request' => $request->except(['_token', 'waybill']),
            ]);

            return redirect()->back()->withInput()->with('error', 'Unable to record dispatch. Please try again.');
        }
    }

    public function waybill(StockDispatch $dispatch): Response|StreamedResponse|RedirectResponse
    {
        try {
            if (!$this->isAuthenticated()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = $this->resolveCompanyId();
            if (!$companyId || $dispatch->company_id !== $companyId) {
                abort(404, 'Waybill not found.');
            }

            if (!$dispatch->waybill_path) {
                abort(404, 'No waybill file for this dispatch.');
            }

            $path = Storage::disk('public')->path($dispatch->waybill_path);
            if (!is_file($path)) {
                abort(404, 'Waybill file not found.');
            }

            return response()->file($path, [
                'Content-Type' => Storage::disk('public')->mimeType($dispatch->waybill_path),
            ]);
        } catch (\Exception $e) {
            Log::error('DispatchController@waybill failed', [
                'error' => $e->getMessage(),
                'dispatch_id' => $dispatch->id ?? null,
            ]);
            abort(404, 'Unable to load waybill.');
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
