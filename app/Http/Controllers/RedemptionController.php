<?php
namespace App\Http\Controllers;

use App\Models\Redemption;
use App\Models\LoyaltyProgram;
use App\Models\Reward;
use App\Models\CustomerPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Mail\RewardRedeemed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RedemptionController extends Controller
{
    public function index($programId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }
    
            $program = LoyaltyProgram::where('company_id', $companyId)
                ->where('user_id', Auth::id())
                ->findOrFail($programId);
    
            // Get pagination per_page value from request or use default
            // $perPage = request()->input('per_page', 5); // Default to 15 items per page
    
            $redemptions = $program->redemptions()
                ->with(['customer', 'reward'])
                ->orderBy('created_at', 'DESC')
                ->paginate(6);
    
            return response()->json([
                'success' => true,
                'data' => $redemptions->items(), // The paginated items
                'meta' => [
                    'current_page' => $redemptions->currentPage(),
                    'last_page' => $redemptions->lastPage(),
                    'per_page' => $redemptions->perPage(),
                    'total' => $redemptions->total(),
                    'from' => $redemptions->firstItem(),
                    'to' => $redemptions->lastItem(),
                ],
                'links' => [
                    'first' => $redemptions->url(1),
                    'last' => $redemptions->url($redemptions->lastPage()),
                    'prev' => $redemptions->previousPageUrl(),
                    'next' => $redemptions->nextPageUrl(),
                ]
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch redemptions',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
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
                'reward_id' => 'required|exists:rewards,id,loyalty_program_id,'.$programId,
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get the reward
            $reward = Reward::findOrFail($request->reward_id);

            // Check customer points
            $customerPoints = CustomerPoint::where('company_id', $companyId)
                ->where('customer_id', $request->customer_id)
                ->where('loyalty_program_id', $programId)
                ->firstOrFail();

            if ($customerPoints->points_balance < $reward->points_required) {
                return response()->json([
                    'success' => false,
                    'error' => 'Customer does not have enough points'
                ], 400);
            }

            // Check reward availability
            if ($reward->quantity !== null && $reward->quantity <= 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Reward is out of stock'
                ], 400);
            }

            // Create redemption
            $redemption = $program->redemptions()->create([
                'company_id' => $companyId,
                'customer_id' => $request->customer_id,
                'reward_id' => $request->reward_id,
                'points_used' => $reward->points_required,
                'status' => 'completed',
                'notes' => $request->notes
            ]);

            // Deduct points
            $customerPoints->points_balance -= $reward->points_required;
            $customerPoints->points_redeemed += $reward->points_required;
            $customerPoints->save();

            // Update reward quantity if limited
            if ($reward->quantity !== null) {
                $reward->quantity--;
                $reward->save();
            }

            // Send email notification
            $customer = $customerPoints->customer;
            Mail::to($customer->email)->send(new RewardRedeemed($customer, $reward, $redemption));

            return response()->json([
                'success' => true,
                'data' => $redemption,
                'message' => 'Reward redeemed successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to process redemption',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function show($programId, $redemptionId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }

            $redemption = Redemption::whereHas('program', function($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->where('user_id', Auth::id());
            })->with(['customer', 'reward'])
              ->findOrFail($redemptionId);

            return response()->json([
                'success' => true,
                'data' => $redemption
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Redemption not found',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 404);
        }
    }

    public function update(Request $request, $programId, $redemptionId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }

            $redemption = Redemption::whereHas('program', function($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->where('user_id', Auth::id());
            })->findOrFail($redemptionId);

            $validator = Validator::make($request->all(), [
                'status' => 'sometimes|required|in:pending,completed,cancelled',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle status changes
            if ($request->has('status') && $request->status !== $redemption->status) {
                if ($request->status === 'cancelled' && $redemption->status === 'completed') {
                    // Refund points
                    $customerPoints = CustomerPoint::where('company_id', $companyId)
                        ->where('customer_id', $redemption->customer_id)
                        ->where('loyalty_program_id', $programId)
                        ->firstOrFail();

                    $customerPoints->points_balance += $redemption->points_used;
                    $customerPoints->points_redeemed -= $redemption->points_used;
                    $customerPoints->save();

                    // Restore reward quantity if limited
                    $reward = $redemption->reward;
                    if ($reward->quantity !== null) {
                        $reward->quantity++;
                        $reward->save();
                    }
                }
            }

            $redemption->update([
                'status' => $request->status ?? $redemption->status,
                'notes' => $request->notes ?? $redemption->notes
            ]);

            return response()->json([
                'success' => true,
                'data' => $redemption,
                'message' => 'Redemption updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update redemption',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function destroy($programId, $redemptionId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }

            $redemption = Redemption::whereHas('program', function($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->where('user_id', Auth::id());
            })->findOrFail($redemptionId);

            // Refund points if completed
            if ($redemption->status === 'completed') {
                $customerPoints = CustomerPoint::where('company_id', $companyId)
                    ->where('customer_id', $redemption->customer_id)
                    ->where('loyalty_program_id', $programId)
                    ->firstOrFail();

                $customerPoints->points_balance += $redemption->points_used;
                $customerPoints->points_redeemed -= $redemption->points_used;
                $customerPoints->save();

                // Restore reward quantity if limited
                $reward = $redemption->reward;
                if ($reward->quantity !== null) {
                    $reward->quantity++;
                    $reward->save();
                }
            }

            $redemption->delete();

            return response()->json([
                'success' => true,
                'message' => 'Redemption deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete redemption',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }


    // In RedemptionController.php
   
public function getCustomers($programId)
{
    try {
        $companyId = Session::get('selected_company_id');
        if (!$companyId) {
            return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
        }

        $program = LoyaltyProgram::where('company_id', $companyId)
            ->where('user_id', Auth::id())
            ->findOrFail($programId);

        $customers = $program->customerPoints()
            ->with(['customer' => function($query) {
                $query->select('id', 'name', 'email');
            }])
            ->get()
            ->map(function($point) {
                return [
                    'id' => $point->customer_id,
                    'name' => $point->customer->name,
                    'email' => $point->customer->email,
                    'points_balance' => $point->points_balance
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $customers
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Failed to fetch customers',
            'details' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}
}