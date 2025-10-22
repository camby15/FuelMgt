<?php
namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\LoyaltyProgram;
use App\Models\Customer;
use App\Models\CustomerPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Mail\ReferralInvitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ReferralController extends Controller
{
    public function index(Request $request, $programId)
{
    try {
        $companyId = Session::get('selected_company_id');
        if (!$companyId) {
            return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
        }

        $program = LoyaltyProgram::where('company_id', $companyId)
            ->where('user_id', Auth::id())
            ->findOrFail($programId);

        $query = $program->referrals()
            ->with(['referrer', 'referee'])
            ->orderBy('created_at', 'DESC');

        // Apply filters
        if ($request->searchReferrer) {
            $query->whereHas('referrer', function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->searchReferrer.'%')
                  ->orWhere('email', 'like', '%'.$request->searchReferrer.'%');
            });
        }

        if ($request->searchEmail) {
            $query->where('email', 'like', '%'.$request->searchEmail.'%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Handle export
        if ($request->export) {
            $referrals = $query->get();
            return $this->exportToCSV($referrals);
        }

        // Paginated results
        $perPage = $request->per_page ?? 10;
        $referrals = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $referrals->items(),
            'meta' => [
                'current_page' => $referrals->currentPage(),
                'last_page' => $referrals->lastPage(),
                'per_page' => $referrals->perPage(),
                'total' => $referrals->total(),
                'from' => $referrals->firstItem(),
                'to' => $referrals->lastItem(),
            ],
            'links' => [
                'first' => $referrals->url(1),
                'last' => $referrals->url($referrals->lastPage()),
                'prev' => $referrals->previousPageUrl(),
                'next' => $referrals->nextPageUrl(),
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Failed to fetch referrals',
            'details' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}

private function exportToCSV($referrals)
{
    $fileName = 'referrals_' . date('Y-m-d') . '.csv';
    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"             => "no-cache",
        "Cache-Control"      => "must-revalidate, post-check=0, pre-check=0",
        "Expires"            => "0"
    ];

    $columns = [
        'Referrer Name', 
        'Referrer Email', 
        'Invited Email', 
        'Referred Customer', 
        'Points Awarded', 
        'Status', 
        'Date Created'
    ];

    // Callback function to

    $callback = function() use($referrals, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($referrals as $referral) {
            $row = [
                $referral->referrer->name ?? 'N/A',
                $referral->referrer->email ?? 'N/A',
                $referral->email,
                $referral->referee->name ?? 'Pending',
                $referral->points_awarded,
                ucfirst($referral->status),
                $referral->created_at->format('Y-m-d H:i:s')
            ];

            fputcsv($file, $row);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}


    public function store(Request $request, $programId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }

            $program = LoyaltyProgram::where('company_id', $companyId)
                ->where('user_id', Auth::id())
                ->findOrFail($programId);

            $validator = Validator::make($request->all(), [
                'customer_id' => 'required|exists:customers,id,company_id,'.$companyId,
                'email' => 'required|email',
                'points_awarded' => 'nullable|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create referral
            $referral = $program->referrals()->create([
                'company_id' => $companyId,
                'referrer_id' => $request->customer_id,
                'email' => $request->email,
                'token' => Str::random(32),
                'points_awarded' => $request->points_awarded ?? 500, // Default 500 points
                'status' => 'pending'
            ]);

            // Send invitation email
            $customer = Customer::find($request->customer_id);
            Mail::to($request->email)->send(new ReferralInvitation($customer, $program, $referral));

            return response()->json([
                'success' => true,
                'data' => $referral,
                'message' => 'Referral created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to create referral',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function show($programId, $referralId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }

            $referral = Referral::whereHas('program', function($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->where('user_id', Auth::id());
            })->with(['referrer', 'referee'])
              ->findOrFail($referralId);

            return response()->json([
                'success' => true,
                'data' => $referral
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Referral not found',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 404);
        }
    }

    public function update(Request $request, $programId, $referralId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }

            $referral = Referral::whereHas('program', function($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->where('user_id', Auth::id());
            })->findOrFail($referralId);

            $validator = Validator::make($request->all(), [
                'status' => 'sometimes|required|in:pending,completed,expired',
                'points_awarded' => 'nullable|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle status change to completed
            if ($request->has('status') && $request->status === 'completed' && $referral->status !== 'completed') {
                // Award points to referrer
                $customerPoints = CustomerPoint::firstOrCreate(
                    [
                        'company_id' => $companyId,
                        'customer_id' => $referral->referrer_id,
                        'loyalty_program_id' => $programId
                    ],
                    [
                        'points_balance' => 0,
                        'points_earned' => 0,
                        'points_redeemed' => 0
                    ]
                );

                $customerPoints->points_balance += $referral->points_awarded;
                $customerPoints->points_earned += $referral->points_awarded;
                $customerPoints->save();

                $referral->completed_at = now();
            }

            $referral->update([
                'status' => $request->status ?? $referral->status,
                'points_awarded' => $request->points_awarded ?? $referral->points_awarded
            ]);

            return response()->json([
                'success' => true,
                'data' => $referral,
                'message' => 'Referral updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update referral',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function destroy($programId, $referralId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }

            $referral = Referral::whereHas('program', function($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->where('user_id', Auth::id());
            })->findOrFail($referralId);

            $referral->delete();

            return response()->json([
                'success' => true,
                'message' => 'Referral deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete referral',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function processReferral($token)
    {
        try {
            $referral = Referral::where('token', $token)
                ->where('status', 'pending')
                ->firstOrFail();

            // Check if referee already exists
            if ($referral->referee_id) {
                return response()->json([
                    'success' => false,
                    'error' => 'Referral already processed'
                ], 400);
            }

            // In a real app, you would create a new customer account here
            // For now, we'll just mark the referral as completed
            $referral->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            // Award points to referrer
            $customerPoints = CustomerPoint::firstOrCreate(
                [
                    'company_id' => $referral->company_id,
                    'customer_id' => $referral->referrer_id,
                    'loyalty_program_id' => $referral->loyalty_program_id
                ],
                [
                    'points_balance' => 0,
                    'points_earned' => 0,
                    'points_redeemed' => 0
                ]
            );

            $customerPoints->points_balance += $referral->points_awarded;
            $customerPoints->points_earned += $referral->points_awarded;
            $customerPoints->save();

            return response()->json([
                'success' => true,
                'message' => 'Referral processed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to process referral',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}