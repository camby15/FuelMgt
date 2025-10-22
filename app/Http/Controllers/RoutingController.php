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

        return view($first . '.' . $second . '.' . $third, [
            'mode' => $mode,
            'demo' => $demo,
        ]);
    }
}
