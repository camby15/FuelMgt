<?php

namespace App\Http\Controllers\FuelManagement\AccountDeposits;

use App\Http\Controllers\Controller;
use App\Models\FuelManagement\AccountDeposits\CompanyFuelAccount;
use App\Models\FuelManagement\FuelStation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class CompanyFuelAccountController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        try {
            $companyId = $this->resolveCompanyId();

            if (!$companyId) {
                return redirect()->back()
                    ->with('error', 'Unable to determine company context.');
            }

            $typeFilter = $request->query('type');
            if ($typeFilter && !in_array($typeFilter, [
                CompanyFuelAccount::TYPE_BANK,
                CompanyFuelAccount::TYPE_CASH,
                CompanyFuelAccount::TYPE_MOBILE_MONEY,
            ], true)) {
                $typeFilter = null;
            }

            $search = $request->query('q');

            $accountsQuery = CompanyFuelAccount::query()
                ->forCompany($companyId)
                ->active()
                ->with(['stations' => fn ($q) => $q->orderBy('name')])
                ->ofType($typeFilter)
                ->search($search)
                ->orderBy('account_name');

            $accounts = $accountsQuery->get();

            $allForSummary = CompanyFuelAccount::query()
                ->forCompany($companyId)
                ->active()
                ->get();

            $summaryTotal = $allForSummary->count();
            $summaryBank = $allForSummary->where('account_type', CompanyFuelAccount::TYPE_BANK)->count();
            $summaryCash = $allForSummary->where('account_type', CompanyFuelAccount::TYPE_CASH)->count();
            $summaryMobile = $allForSummary->where('account_type', CompanyFuelAccount::TYPE_MOBILE_MONEY)->count();

            $distinctStationIds = DB::table('cf_account_station')
                ->join('company_fuel_accounts', 'company_fuel_accounts.id', '=', 'cf_account_station.company_fuel_account_id')
                ->where('company_fuel_accounts.company_id', $companyId)
                ->whereNull('company_fuel_accounts.deleted_at')
                ->where('company_fuel_accounts.status', CompanyFuelAccount::STATUS_ACTIVE)
                ->distinct()
                ->pluck('fuel_station_id');

            $summaryStationsCovered = $distinctStationIds->count();

            $stations = FuelStation::forCompany($companyId)
                ->select('id', 'name', 'code', 'location')
                ->orderBy('name')
                ->get();

            return view('company.FuelManagement.allaccount', [
                'accounts' => $accounts,
                'stations' => $stations,
                'companyId' => $companyId,
                'summaryTotal' => $summaryTotal,
                'summaryBank' => $summaryBank,
                'summaryCash' => $summaryCash,
                'summaryMobileMoney' => $summaryMobile,
                'summaryStationsCovered' => $summaryStationsCovered,
                'typeFilter' => $typeFilter,
                'searchQuery' => $search,
            ]);
        } catch (\Exception $e) {
            Log::error('CompanyFuelAccountController@index failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
            ]);

            return redirect()->back()->with('error', 'Unable to load accounts at the moment.');
        }
    }

    public function show(CompanyFuelAccount $company_fuel_account): JsonResponse|RedirectResponse
    {
        $companyId = $this->resolveCompanyId();

        if (!$companyId || (int) $company_fuel_account->company_id !== (int) $companyId) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        $company_fuel_account->load(['stations' => fn ($q) => $q->orderBy('name')]);

        return response()->json([
            'id' => $company_fuel_account->id,
            'account_code' => $company_fuel_account->account_code,
            'account_name' => $company_fuel_account->account_name,
            'account_type' => $company_fuel_account->account_type,
            'account_type_label' => $company_fuel_account->typeLabel(),
            'description' => $company_fuel_account->description,
            'notes' => $company_fuel_account->notes,
            'bank_name' => $company_fuel_account->bank_name,
            'bank_account_no' => $company_fuel_account->bank_account_no,
            'bank_branch' => $company_fuel_account->bank_branch,
            'mobile_money_provider' => $company_fuel_account->mobile_money_provider,
            'mobile_money_number' => $company_fuel_account->mobile_money_number,
            'last_reconciled_at' => $company_fuel_account->last_reconciled_at?->toDateString(),
            'last_reconciled_display' => $company_fuel_account->last_reconciled_at?->format('F Y'),
            'stations' => $company_fuel_account->stations->map(fn (FuelStation $s) => [
                'id' => $s->id,
                'name' => $s->name,
                'code' => $s->code,
            ]),
            'stations_label' => $company_fuel_account->stations->pluck('name')->filter()->implode(', '),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $companyId = $this->resolveCompanyId();

            if (!$companyId) {
                return redirect()->back()->with('error', 'Unable to determine company context.');
            }

            $validated = $request->validate([
                'account_type' => ['required', 'in:' . implode(',', [
                    CompanyFuelAccount::TYPE_BANK,
                    CompanyFuelAccount::TYPE_CASH,
                    CompanyFuelAccount::TYPE_MOBILE_MONEY,
                ])],
                'account_code' => [
                    'required',
                    'string',
                    'max:191',
                    Rule::unique('company_fuel_accounts', 'account_code')->where(fn ($q) => $q->where('company_id', $companyId)),
                ],
                'account_name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:500'],
                'bank_name' => ['nullable', 'string', 'max:255'],
                'bank_account_no' => ['nullable', 'string', 'max:191'],
                'bank_branch' => ['nullable', 'string', 'max:255'],
                'mobile_money_provider' => ['nullable', 'string', 'max:191'],
                'mobile_money_number' => ['nullable', 'string', 'max:191'],
                'notes' => ['nullable', 'string'],
                'last_reconciled_at' => ['nullable', 'date'],
                'stations' => ['nullable', 'array'],
                'stations.*' => ['integer', 'exists:fuel_stations,id'],
            ]);

            if ($validated['account_type'] === CompanyFuelAccount::TYPE_BANK) {
                $request->validate([
                    'bank_name' => ['required', 'string', 'max:255'],
                    'bank_account_no' => ['required', 'string', 'max:191'],
                ]);
            }

            if ($validated['account_type'] === CompanyFuelAccount::TYPE_MOBILE_MONEY) {
                $request->validate([
                    'mobile_money_provider' => ['required', 'string', 'max:191'],
                    'mobile_money_number' => ['required', 'string', 'max:191'],
                ]);
            }

            $stationIds = collect($validated['stations'] ?? [])
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            if ($stationIds->isNotEmpty()) {
                $validCount = FuelStation::forCompany($companyId)
                    ->whereIn('id', $stationIds)
                    ->count();

                if ($validCount !== $stationIds->count()) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'One or more selected stations are invalid for this company.');
                }
            }

            DB::beginTransaction();

            $account = CompanyFuelAccount::create([
                'company_id' => $companyId,
                'account_type' => $validated['account_type'],
                'account_code' => $validated['account_code'],
                'account_name' => $validated['account_name'],
                'description' => $validated['description'] ?? null,
                'bank_name' => $validated['bank_name'] ?? null,
                'bank_account_no' => $validated['bank_account_no'] ?? null,
                'bank_branch' => $validated['bank_branch'] ?? null,
                'mobile_money_provider' => $validated['mobile_money_provider'] ?? null,
                'mobile_money_number' => $validated['mobile_money_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'last_reconciled_at' => $validated['last_reconciled_at'] ?? null,
                'status' => CompanyFuelAccount::STATUS_ACTIVE,
                'created_by' => $this->getAuthenticatedUserId(),
                'updated_by' => $this->getAuthenticatedUserId(),
            ]);

            if ($stationIds->isNotEmpty()) {
                $account->stations()->sync($stationIds->all());
            }

            DB::commit();

            return redirect()->route('company.fuel.accounts.index')->with('success', 'Account saved successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('CompanyFuelAccountController@store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Unable to save account. Please try again.');
        }
    }

    private function resolveCompanyId(): ?int
    {
        $companyId = Session::get('selected_company_id');

        if ($companyId) {
            return (int) $companyId;
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

            return (int) $companyId;
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
