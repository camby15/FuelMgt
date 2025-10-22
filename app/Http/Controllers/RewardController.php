<?php
namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\LoyaltyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RewardController extends Controller
{
    public function index(Request $request, $programId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'Company session expired'
                ]);
            }
    
            $program = LoyaltyProgram::where('company_id', $companyId)
                ->where('user_id', Auth::id())
                ->find($programId);
    
            if (!$program) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'Program not found'
                ]);
            }
    
            $query = $program->rewards()
                ->where('is_active', true)
                ->select('id', 'name', 'points_required', 'description', 'quantity');
    
            // Filter by max points if provided
            if ($request->has('max_points')) {
                $query->where('points_required', '<=', $request->max_points);
            }
    
            // Filter out rewards with zero quantity
            $query->where(function($q) {
                $q->whereNull('quantity')
                  ->orWhere('quantity', '>', 0);
            });
    
            $rewards = $query->get();
    
            return response()->json([
                'success' => true,
                'data' => $rewards->isEmpty() ? null : $rewards,
                'message' => $rewards->isEmpty() ? 'No rewards available' : null
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch rewards',
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
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'points_required' => 'required|integer|min:1',
                'quantity' => 'nullable|integer|min:0',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after:start_date',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $reward = $program->rewards()->create([
                'name' => $request->name,
                'description' => $request->description,
                'points_required' => $request->points_required,
                'quantity' => $request->quantity,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active ?? true
            ]);

            return response()->json([
                'success' => true,
                'data' => $reward,
                'message' => 'Reward created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to create reward',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function show($programId, $rewardId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }

            $reward = Reward::whereHas('program', function($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->where('user_id', Auth::id());
            })->findOrFail($rewardId);

            return response()->json([
                'success' => true,
                'data' => $reward
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Reward not found',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 404);
        }
    }

    public function update(Request $request, $programId, $rewardId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }

            $reward = Reward::whereHas('program', function($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->where('user_id', Auth::id());
            })->findOrFail($rewardId);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'points_required' => 'sometimes|required|integer|min:1',
                'quantity' => 'nullable|integer|min:0',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after:start_date',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $reward->update([
                'name' => $request->name ?? $reward->name,
                'description' => $request->description ?? $reward->description,
                'points_required' => $request->points_required ?? $reward->points_required,
                'quantity' => $request->quantity ?? $reward->quantity,
                'start_date' => $request->start_date ?? $reward->start_date,
                'end_date' => $request->end_date ?? $reward->end_date,
                'is_active' => $request->has('is_active') ? $request->is_active : $reward->is_active
            ]);

            return response()->json([
                'success' => true,
                'data' => $reward,
                'message' => 'Reward updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update reward',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function destroy($programId, $rewardId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }

            $reward = Reward::whereHas('program', function($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->where('user_id', Auth::id());
            })->findOrFail($rewardId);

            $reward->delete();

            return response()->json([
                'success' => true,
                'message' => 'Reward deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete reward',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }


    public function getRewards($programId)
    {
        try {
            $currentDate = now()->format('Y-m-d');
            // 1. Get active rewards for the specified program
            $rewards = Reward::where('loyalty_program_id', $programId)
                ->where('is_active', 1)
                ->where(function($q) {
                    $q->whereNull('quantity')  // Unlimited rewards
                      ->orWhere('quantity', '>', 0);  // Or quantity available
                })
                ->where(function($q) use ($currentDate) {
                    $q->whereNull('start_date')  // No start date restriction
                      ->orWhere('start_date', '<=', $currentDate);  // Or started already
                })
                ->where(function($q) use ($currentDate) {
                    $q->whereNull('end_date')  // No expiration
                      ->orWhere('end_date', '>=', $currentDate);  // Or not expired yet
                })
                ->get(['id', 'name', 'points_required', 'quantity']);
            
            // 2. Return response
            return response()->json([
                'success' => true,
                'data' => $rewards,
                'debug' => [
                    'program_id' => $programId,
                    'reward_count' => count($rewards),
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load rewards',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}