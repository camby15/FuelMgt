<?php

namespace App\Http\Controllers;

use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class SuperUserLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('stak.admin');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $superAdmin = SuperAdmin::where('username', $request->username)->first();

        if ($superAdmin && Hash::check($request->password, $superAdmin->password)) {
            Auth::guard('super_admin')->login($superAdmin);
            return Redirect::route('superAdmin.dashboard');
        }

        return back()->withErrors([
            'username' => 'Invalid credentials',
        ])->withInput();
    }

    public function showDashboard()
    {
        return view('superAdmin.dashboard');
    }

    public function logout()
    {
        Auth::guard('super_admin')->logout();
        return Redirect::route('superAdmin.login');
    }
}
