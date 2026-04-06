<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoutingController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth')->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()) {
            return redirect('index');
        } else {
            return redirect('login');
        }
    }

    /**
     * Display a view based on first route param
     *
     * @return \Illuminate\Http\Response
     */
    public function root(Request $request, $first)
    {
        $mode = $request->query('mode');
        $demo = $request->query('demo');

        if ($first == 'assets') {
            return redirect('home');
        }

    }

    /**
     * second level route
     */
    public function secondLevel(Request $request, $first, $second)
    {
        $mode = $request->query('mode');
        $demo = $request->query('demo');

        // Special handling for images and static assets
        if ($first === 'images') {
            $publicPath = public_path("images/{$second}");
            
            if (file_exists($publicPath)) {
                return response()->file($publicPath);
            }
            
            Log::warning("Image not found: {$publicPath}");
            return response()->json(['error' => 'Image not found'], 404);
        }

        if ($first == 'assets') {
            return redirect('home');
        }

        // Special handling for newsletters
        if ($first === 'company' && $second === 'newsletters') {
            return view('company.Digital-marketing.newsletter-templates', [
                'mode' => $mode,
                'demo' => $demo
            ]);
        }

        // Special handling for CRM route
        if ($first === 'company' && $second === 'crm') {
            return view('company.CRM.crm', ['mode' => $mode, 'demo' => $demo]);
        }

        return view($first . '.' . $second, ['mode' => $mode, 'demo' => $demo]);
    }

    /**
     * third level route
     */
    public function thirdLevel(Request $request, $first, $second, $third)
    {
        $mode = $request->query('mode');
        $demo = $request->query('demo');

        if ($first == 'assets') {
            return redirect('home');
        }

        // Special handling for newsletters
        if ($first === 'company' && $second === 'newsletters') {
            return view('company.Digital-marketing.newsletter-templates', [
                'mode' => $mode,
                'demo' => $demo
            ]);
        }

        // Special handling for fuel attendants
        if ($first === 'company' && $second === 'FuelManagement' && $third === 'attendants') {
            $companyId = session('selected_company_id');
            $stations = \App\Models\FuelManagement\FuelStation::forCompany($companyId)
                ->select('id', 'name', 'code', 'location')
                ->orderBy('name')
                ->get();
            return view('company.FuelManagement.attendants', [
                'mode' => $mode,
                'demo' => $demo,
                'stations' => $stations,
                'attendants' => collect(),
                'company' => null,
                'companyId' => $companyId,
                'lastSyncedAt' => now(),
            ]);
        }

        // Special handling for roster management
        if ($first === 'company' && $second === 'FuelManagement' && $third === 'roasterManagement') {
            $companyId = session('selected_company_id');
            $stations = \App\Models\FuelManagement\FuelStation::forCompany($companyId)
                ->select('id', 'name', 'code', 'location')
                ->orderBy('name')
                ->get();
            return view('company.FuelManagement.roasterManagement', [
                'mode' => $mode,
                'demo' => $demo,
                'stations' => $stations,
                'attendants' => collect(),
                'rosters' => collect(),
                'rostersByAttendant' => collect(),
                'weekStartDate' => now()->startOfWeek()->format('Y-m-d'),
                'stationId' => null,
                'managerStation' => null,
                'totalAttendants' => 0,
                'scheduledAttendants' => 0,
                'coverageScore' => 0,
                'company' => null,
                'companyId' => $companyId,
            ]);
        }

        // Redirect to proper stock controller
        if ($first === 'company' && $second === 'FuelManagement' && $third === 'stock') {
            return redirect()->route('company.fuel.stocks.index');
        }

        // Redirect to proper station managers controller
        if ($first === 'company' && $second === 'FuelManagement' && $third === 'stationmanager') {
            return redirect()->route('company.fuel.station-managers.index');
        }

        // Redirect to proper dispatches controller
        if ($first === 'company' && $second === 'FuelManagement' && $third === 'DispatchStock') {
            return redirect()->route('company.fuel.dispatches.index');
        }

        // Redirect to proper reconciliation controller
        if ($first === 'company' && $second === 'FuelManagement' && $third === 'stockRecon') {
            return redirect()->route('company.fuel.reconciliations.index');
        }

        // Redirect All Accounts to fuel accounts controller
        if ($first === 'company' && $second === 'FuelManagement' && $third === 'allaccount') {
            return redirect()->route('company.fuel.accounts.index');
        }

        // Redirect Bank Deposit view to fuel bank deposits controller
        if ($first === 'company' && $second === 'FuelManagement' && in_array($third, ['bankDeposit', 'bankdeposit'], true)) {
            return redirect()->route('company.fuel.bank-deposits.index');
        }

        return view($first . '.' . $second . '.' . $third, [
            'mode' => $mode,
            'demo' => $demo,
        ]);
    }
}
