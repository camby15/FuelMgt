<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\CompanySubUser;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class CompanyUserProfileController extends Controller
{
    /**
     * Display a listing of user profiles.
     */
    public function index(): View
    {
        $companyId = Session::get('selected_company_id');
        
        if (!$companyId) {
            return redirect()->back()->with('error', 'Company session expired. Please login again.');
        }

        $profiles = UserProfile::where('company_id', $companyId)
            ->withCount('subUsers')
            ->orderBy('created_at', 'desc')
            ->get();

        $subUsers = CompanySubUser::where('company_id', $companyId)
            ->with('profile')
            ->get(['id', 'fullname', 'email', 'profile_id']);

        return view('company.user-management.user-profile', compact('profiles', 'subUsers'));
    }

    /**
     * Store a newly created profile.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            $request->validate([
                'profile_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive'
            ]);

            $profile = UserProfile::create([
                'company_id' => $companyId,
                'profile_name' => $request->profile_name,
                'description' => $request->description,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile created successfully',
                'profile' => $profile
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating profile: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified profile.
     */
    public function edit(UserProfile $profile): JsonResponse
    {
        return response()->json([
            'success' => true,
            'profile' => $profile
        ]);
    }

    /**
     * Update the specified user profile.
     */
    public function update(Request $request, UserProfile $profile)
    {
        try {
            $request->validate([
                'status' => 'required|in:active,inactive',
                'description' => 'nullable|string|max:500'
            ]);

            $profile->update([
                'status' => $request->status,
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the profile.'
            ]);
        }
    }

    /**
     * Remove the specified user profile.
     */
    public function destroy(UserProfile $profile)
    {
        try {
            // Check if profile has any users
            if ($profile->subUsers()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete profile. Please remove all users from this profile first.'
                ]);
            }

            $profile->delete();

            return response()->json([
                'success' => true,
                'message' => 'Profile deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting profile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the profile.'
            ]);
        }
    }

    /**
     * Get menu access for a specific profile
     */
    public function getMenuAccess(UserProfile $profile)
    {
        try {
            $menuAccess = $profile->menuAccess()->get();
            return response()->json([
                'success' => true,
                'menu_access' => $menuAccess
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting menu access: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get menu access'
            ]);
        }
    }

    /**
     * Update menu access for a specific profile
     */
    public function updateMenuAccess(Request $request, UserProfile $profile)
    {
        try {
            $request->validate([
                'menu_access' => 'required|array',
                'menu_access.*.menu_key' => 'required|string',
                'menu_access.*.menu_name' => 'required|string',
                'menu_access.*.menu_icon' => 'nullable|string',
                'menu_access.*.menu_route' => 'nullable|string',
                'menu_access.*.parent_menu' => 'nullable|string',
                'menu_access.*.is_active' => 'required|boolean'
            ]);

            DB::beginTransaction();

            // Delete existing menu access
            $profile->menuAccess()->delete();

            // Create new menu access
            $profile->menuAccess()->createMany($request->menu_access);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Menu access updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating menu access: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update menu access'
            ]);
        }
    }

    /**
     * Get all sub-users for the current company.
     */
    public function getSubUsers(): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            $subUsers = CompanySubUser::where('company_id', $companyId)
                ->with('profile')
                ->get(['id', 'fullname', 'email', 'profile_id']);

            return response()->json([
                'success' => true,
                'subUsers' => $subUsers
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching sub users: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sub users'
            ], 500);
        }
    }

    /**
     * Get sub-users for profile assignment.
     */
    public function getSubUsersForProfileAssignment(): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            $subUsers = CompanySubUser::where('company_id', $companyId)->get();
            $profiles = UserProfile::where('company_id', $companyId)
                ->where('status', 'active')
                ->get();

            return response()->json([
                'success' => true,
                'subUsers' => $subUsers,
                'profiles' => $profiles
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching sub-users: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sub-users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign a profile to a user.
     */
    public function assignProfile(Request $request, $userId): JsonResponse
    {
        try {
            $request->validate([
                'profile_id' => 'required|exists:user_profiles,id'
            ]);

            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            $user = CompanySubUser::where('company_id', $companyId)
                ->where('id', $userId)
                ->firstOrFail();

            $user->profile_id = $request->profile_id;
            $user->save();

            // Get updated profile counts
            $profiles = UserProfile::where('company_id', $companyId)
                ->withCount('subUsers')
                ->get(['id', 'profile_name', 'sub_users_count']);

            return response()->json([
                'success' => true,
                'message' => 'Profile assigned successfully',
                'profiles' => $profiles
            ]);
        } catch (\Exception $e) {
            Log::error('Error assigning profile: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'company_id' => Session::get('selected_company_id'),
                'user_id' => $userId,
                'profile_id' => $request->profile_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to assign profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get menu access for a specific user.
     */
    public function getUserMenuAccess($userId): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            $user = CompanySubUser::where('company_id', $companyId)
                ->where('id', $userId)
                ->with(['profile.menuAccess' => function($query) {
                    $query->where('is_active', true);
                }])
                ->firstOrFail();

            if (!$user->profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'User has no profile assigned'
                ]);
            }

            // Format the menu access data
            $formattedMenuAccess = collect($user->profile->menuAccess)
                ->map(function ($menu) {
                    return [
                        'menu_name' => $menu->menu_name ?? 'Unnamed Menu',
                        'access_type' => $menu->is_active ? 'Full Access' : 'No Access',
                        'menu_key' => $menu->menu_key ?? '',
                        'parent_key' => $menu->parent_menu ?? null,
                        'icon' => $menu->menu_icon ?? 'ri-menu-line'
                    ];
                });

            return response()->json([
                'success' => true,
                'menuAccess' => $formattedMenuAccess
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching user menu access: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'company_id' => Session::get('selected_company_id'),
                'user_id' => $userId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch menu access: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove profile assignment from a user.
     */
    public function removeProfile($userId): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            $user = CompanySubUser::where('company_id', $companyId)
                ->where('id', $userId)
                ->firstOrFail();

            $user->profile_id = null;
            $user->save();

            // Get updated profile counts
            $profiles = UserProfile::where('company_id', $companyId)
                ->withCount('subUsers')
                ->get(['id', 'profile_name', 'sub_users_count']);

            return response()->json([
                'success' => true,
                'message' => 'Profile assignment removed successfully',
                'profiles' => $profiles
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing profile assignment: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'company_id' => Session::get('selected_company_id'),
                'user_id' => $userId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to remove profile assignment: ' . $e->getMessage()
            ], 500);
        }
    }
}