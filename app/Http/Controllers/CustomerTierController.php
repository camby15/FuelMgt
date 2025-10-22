<?php
namespace App\Http\Controllers;

use App\Models\CustomerTier;
use App\Models\LoyaltyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CustomerTierController extends Controller
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
    
            // Add pagination with 10 items per page (you can adjust this number)
            $tiers = $program->tiers()
                ->orderBy('points_required')
                ->paginate(request('per_page', 5)); // Default 10 per page, customizable via request
    
            return response()->json([
                'success' => true,
                'data' => $tiers->items(), // The paginated items
                'pagination' => [
                    'total' => $tiers->total(),
                    'per_page' => $tiers->perPage(),
                    'current_page' => $tiers->currentPage(),
                    'last_page' => $tiers->lastPage(),
                    'from' => $tiers->firstItem(),
                    'to' => $tiers->lastItem()
                ]
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch tiers',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function store(Request $request, $programId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Company session expired'
                ], 403);
            }
    
            // Find program with validation
            $program = LoyaltyProgram::where('company_id', $companyId)
                ->where('user_id', Auth::id())
                ->find($programId);
    
            if (!$program) {
                return response()->json([
                    'success' => false,
                    'error' => 'Loyalty program not found'
                ], 404);
            }
    
            // Validate with custom messages
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'benefits' => 'nullable|string',
                'points_required' => 'required|integer|min:0'
            ], [
                'name.required' => 'The tier name is required',
                'points_required.required' => 'Points requirement is required',
                'points_required.min' => 'Points must be a positive number'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
    
            // Create tier with position
            $tier = $program->tiers()->create([
                'name' => $request->name,
                'benefits' => $request->benefits,
                'points_required' => $request->points_required,
                'position' => $program->tiers()->count() + 1
            ]);
    
            return response()->json([
                'success' => true,
                'data' => $tier,
                'message' => 'Tier created successfully'
            ], 201);
    
        } catch (\Exception $e) {
            Log::error('Failed to create tier: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to create tier',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function show($programId, $tierId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }

            $tier = CustomerTier::whereHas('program', function($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->where('user_id', Auth::id());
            })->findOrFail($tierId);

            return response()->json([
                'success' => true,
                'data' => $tier
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Tier not found',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 404);
        }
    }

    public function update(Request $request, $programId, $tierId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }

            $tier = CustomerTier::whereHas('program', function($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->where('user_id', Auth::id());
            })->findOrFail($tierId);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'benefits' => 'nullable|string',
                'points_required' => 'sometimes|required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $tier->update([
                'name' => $request->name ?? $tier->name,
                'benefits' => $request->benefits ?? $tier->benefits,
                'points_required' => $request->points_required ?? $tier->points_required
            ]);

            return response()->json([
                'success' => true,
                'data' => $tier,
                'message' => 'Tier updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update tier',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function destroy($programId, $tierId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }

            $tier = CustomerTier::whereHas('program', function($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->where('user_id', Auth::id());
            })->findOrFail($tierId);

            $tier->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tier deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete tier',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}