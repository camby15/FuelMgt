<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\DemoRequestReceived;
use App\Mail\DemoRequestConfirmation;
use Exception;

class DemoRequestController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'company' => 'nullable|string|max:255',
                'phone' => 'required|string|max:20',
            ]);
    
            // Send emails (using queue)
            Mail::to('info@shrinqghana.com')->queue(
                new DemoRequestReceived($validated)
            );
    
            Mail::to($validated['email'])->queue(
                new DemoRequestConfirmation($validated)
            );
    
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your demo request has been submitted. We will contact you shortly.'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please correct the errors in the form',
                'errors' => $e->errors()
            ], 422);
            
        } catch (Exception $e) {
            Log::error('Demo request error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request. Please try again later.'
            ], 500);
        }
    }
}