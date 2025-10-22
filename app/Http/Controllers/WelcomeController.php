<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Controller for handling welcome page and initial access granting
 * Manages the flow from landing page to authentication pages
 */
class WelcomeController extends Controller
{
    /**
     * Show the welcome/landing page.
     * This is the entry point of the application.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // return redirect()->route('auth.login');
        return view('welcome.index');
    }

    /**
     * Grant access when user clicks "Get Started"
     * Sets a session flag to allow access to auth pages
     * This prevents users from accessing auth pages directly through URLs
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function grantAccess()
    {
        // Set the access_granted session flag
        Session::put('access_granted', true);

        // Redirect to login page
        return redirect()->route('auth.login');
    }
}
