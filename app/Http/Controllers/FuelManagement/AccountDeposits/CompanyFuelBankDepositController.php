<?php

namespace App\Http\Controllers\FuelManagement\AccountDeposits;

use App\Http\Controllers\Controller;
use App\Models\FuelManagement\AccountDeposits\CompanyFuelBankDeposit;
use App\Models\FuelManagement\FuelStation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CompanyFuelBankDepositController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        try {
            $companyId = $this->resolveCompanyId();

            if (!$companyId) {
                return redirect()->back()
                    ->with('error', 'Unable to determine company context.');
            }

            $perPage = (int) $request->query('per_page', 50);
            if (!in_array($perPage, [10, 25, 50, 100], true)) {
                $perPage = 50;
            }

            $from = $request->query('from');
            $to = $request->query('to');
            $stationFilter = $request->query('site');
            $search = $request->query('search');

            $query = CompanyFuelBankDeposit::query()
                ->forCompany($companyId)
                ->with(['station'])
                ->transactionDateBetween(
                    is_string($from) && $from !== '' ? $from : null,
                    is_string($to) && $to !== '' ? $to : null
                )
                ->search(is_string($search) && $search !== '' ? $search : null)
                ->orderByDesc('transaction_date')
                ->orderByDesc('id');

            if ($stationFilter !== null && $stationFilter !== '') {
                $sid = (int) $stationFilter;
                if ($sid > 0) {
                    $valid = FuelStation::forCompany($companyId)->whereKey($sid)->exists();
                    if ($valid) {
                        $query->forStation($sid);
                    }
                }
            }

            $deposits = $query->paginate($perPage)->withQueryString();

            $stations = FuelStation::forCompany($companyId)
                ->select('id', 'name', 'code', 'location')
                ->orderBy('name')
                ->get();

            $sites = $stations->map(fn (FuelStation $s) => [
                'value' => (string) $s->id,
                'label' => $s->name,
            ])->all();

            $today = now()->startOfDay();
            $todayRows = CompanyFuelBankDeposit::query()
                ->forCompany($companyId)
                ->whereDate('transaction_date', $today)
                ->get(['amount', 'fuel_station_id', 'payment_mode']);

            $totalTodayAmt = (float) $todayRows->sum('amount');
            $cashSum = (float) $todayRows->where('payment_mode', CompanyFuelBankDeposit::PAYMENT_CASH)->sum('amount');
            $cashPctToday = $totalTodayAmt > 0 ? (int) round(100 * $cashSum / $totalTodayAmt) : 0;
            $transferTotalToday = (float) $todayRows->whereIn('payment_mode', [
                CompanyFuelBankDeposit::PAYMENT_BANK_TRANSFER,
                CompanyFuelBankDeposit::PAYMENT_CHEQUE,
            ])->sum('amount');

            $metrics = [
                'today_value' => $todayRows->sum('amount'),
                'today_stations' => $todayRows->pluck('fuel_station_id')->unique()->count(),
                'deposits_captured_count' => $todayRows->count(),
                'pending_proof_count' => CompanyFuelBankDeposit::query()
                    ->forCompany($companyId)
                    ->whereNull('proof_path')
                    ->count(),
                'cash_pct_today' => $cashPctToday,
                'transfer_total_today' => $transferTotalToday,
            ];

            return view('company.FuelManagement.bankDeposit', [
                'deposits' => $deposits,
                'sites' => $sites,
                'stations' => $stations,
                'companyId' => $companyId,
                'metrics' => $metrics,
            ]);
        } catch (\Exception $e) {
            Log::error('CompanyFuelBankDepositController@index failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
            ]);

            return redirect()->back()->with('error', 'Unable to load bank deposits at the moment.');
        }
    }

    public function show(CompanyFuelBankDeposit $company_fuel_bank_deposit): JsonResponse
    {
        $companyId = $this->resolveCompanyId();

        if (!$companyId || (int) $company_fuel_bank_deposit->company_id !== (int) $companyId) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        $company_fuel_bank_deposit->load('station');

        $station = $company_fuel_bank_deposit->station;

        return response()->json([
            'id' => $company_fuel_bank_deposit->id,
            'transaction_date' => $company_fuel_bank_deposit->transaction_date?->format('d-M-Y'),
            'transaction_date_iso' => $company_fuel_bank_deposit->transaction_date?->toDateString(),
            'sales_date' => $company_fuel_bank_deposit->sales_date?->format('d-M-Y'),
            'sales_date_iso' => $company_fuel_bank_deposit->sales_date?->toDateString(),
            'station_name' => $station?->name,
            'account_name' => $company_fuel_bank_deposit->account_name,
            'account_number' => $company_fuel_bank_deposit->account_number,
            'amount' => $company_fuel_bank_deposit->amount,
            'amount_formatted' => 'GHS' . number_format((float) $company_fuel_bank_deposit->amount, 2),
            'deposit_by' => $company_fuel_bank_deposit->deposit_by,
            'narration' => $company_fuel_bank_deposit->narration,
            'details' => $company_fuel_bank_deposit->details,
            'payment_mode' => $company_fuel_bank_deposit->payment_mode,
            'transaction_id' => $company_fuel_bank_deposit->transaction_id,
            'proof_url' => $company_fuel_bank_deposit->proof_path
                ? Storage::disk('public')->url($company_fuel_bank_deposit->proof_path)
                : null,
            'created_at' => $company_fuel_bank_deposit->created_at?->format('d M Y H:i'),
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
                'transaction_date' => ['required', 'date'],
                'sales_date' => ['required', 'date'],
                'fuel_station_id' => ['required', 'integer'],
                'account_name' => ['required', 'string', 'max:255'],
                'account_number' => ['required', 'string', 'max:191'],
                'amount' => ['required', 'numeric', 'min:0'],
                'deposit_by' => ['required', 'string', 'max:255'],
                'narration' => ['required', 'string'],
                'details' => ['nullable', 'string'],
                'payment_mode' => ['required', 'string', 'in:' . implode(',', CompanyFuelBankDeposit::paymentModes())],
                'transaction_id' => ['required', 'string', 'max:191'],
                'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            ]);

            $stationId = (int) $validated['fuel_station_id'];
            $stationOk = FuelStation::forCompany($companyId)->whereKey($stationId)->exists();

            if (!$stationOk) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'The selected station is invalid for this company.');
            }

            $proofPath = null;
            $proofName = null;

            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $file = $request->file('file');
                $proofName = $file->getClientOriginalName();
                $proofPath = $file->store("bank-deposits/{$companyId}", 'public');
            }

            DB::beginTransaction();

            CompanyFuelBankDeposit::create([
                'company_id' => $companyId,
                'fuel_station_id' => $stationId,
                'transaction_date' => $validated['transaction_date'],
                'sales_date' => $validated['sales_date'],
                'account_name' => $validated['account_name'],
                'account_number' => $validated['account_number'],
                'amount' => $validated['amount'],
                'deposit_by' => $validated['deposit_by'],
                'narration' => $validated['narration'],
                'details' => $validated['details'] ?? null,
                'payment_mode' => $validated['payment_mode'],
                'transaction_id' => $validated['transaction_id'],
                'proof_path' => $proofPath,
                'proof_original_name' => $proofName,
                'created_by' => $this->getAuthenticatedUserId(),
                'updated_by' => $this->getAuthenticatedUserId(),
            ]);

            DB::commit();

            return redirect()->route('company.fuel.bank-deposits.index')->with('success', 'Bank deposit recorded successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('CompanyFuelBankDepositController@store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Unable to save bank deposit. Please try again.');
        }
    }

    public function destroy(CompanyFuelBankDeposit $company_fuel_bank_deposit): RedirectResponse
    {
        try {
            $companyId = $this->resolveCompanyId();

            if (!$companyId || (int) $company_fuel_bank_deposit->company_id !== (int) $companyId) {
                return redirect()->back()->with('error', 'Deposit not found.');
            }

            $company_fuel_bank_deposit->delete();

            return redirect()->route('company.fuel.bank-deposits.index')->with('success', 'Bank deposit removed.');
        } catch (\Exception $e) {
            Log::error('CompanyFuelBankDepositController@destroy failed', [
                'error' => $e->getMessage(),
                'id' => $company_fuel_bank_deposit->id,
            ]);

            return redirect()->back()->with('error', 'Unable to delete deposit.');
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
