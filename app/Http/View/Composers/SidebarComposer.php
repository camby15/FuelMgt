<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SidebarComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $userMenuAccess = collect();
        $isSubUser = false;
        
        // Check if we have a sub_user_id in session (sub-user is logged in)
        if (session('sub_user_id') && auth()->guard('sub_user')->check()) {
            $currentUser = auth()->guard('sub_user')->user();
            $isSubUser = true;
            
            // Get the user's profile and menu access
            if ($currentUser && $currentUser->profile) {
                $userMenuAccess = $currentUser->profile->menuAccess()
                    ->where('is_active', true)
                    ->get()
                    ->keyBy('menu_key');
                    
            }
        }
        
        // Helper function to check if user has access to a menu
        $hasMenuAccess = function($menuKey) use ($userMenuAccess, $isSubUser) {
            if (!$isSubUser) {
                return true; // Company owners have full access
            }
            return $userMenuAccess->has($menuKey);
        };
        
        // Helper function to check if user has access to any submenu
        $hasSubmenuAccess = function($parentKey) use ($userMenuAccess, $isSubUser) {
            if (!$isSubUser) {
                return true; // Company owners have full access
            }
            return $userMenuAccess->where('parent_menu', $parentKey)->count() > 0;
        };

        $view->with([
            'userMenuAccess' => $userMenuAccess,
            'isSubUser' => $isSubUser,
            'hasMenuAccess' => $hasMenuAccess,
            'hasSubmenuAccess' => $hasSubmenuAccess
        ]);
    }
}
