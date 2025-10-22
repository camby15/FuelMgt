<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin\SuperRole;
use App\Models\SuperAdmin\SuperPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SuperRoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index()
    {
        try {
            $query = SuperRole::with('permissions');
            
            // Search by role name
            if ($search = request('search')) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('display_name', 'like', "%{$search}%");
            }
            
            // Filter by status
            if (request()->has('status') && in_array(request('status'), ['active', 'inactive'])) {
                $query->where('is_active', request('status') === 'active');
            }
            
            // Sorting
            $sortField = request('sort', 'created_at');
            $sortDirection = request('direction', 'desc');
            
            if (in_array($sortField, ['name', 'created_at', 'updated_at'])) {
                $query->orderBy($sortField, $sortDirection);
            }
            
            $perPage = request('per_page', 10);
            $roles = $query->paginate($perPage);
                
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $roles->items(),
                    'pagination' => [
                        'total' => $roles->total(),
                        'per_page' => $roles->perPage(),
                        'current_page' => $roles->currentPage(),
                        'last_page' => $roles->lastPage(),
                        'next_page_url' => $roles->nextPageUrl(),
                        'prev_page_url' => $roles->previousPageUrl(),
                        'from' => $roles->firstItem(),
                        'to' => $roles->lastItem()
                    ]
                ]);
            }
                
            return view('superAdmin.roles', ['roles' => $roles]);
                
        } catch (\Exception $e) {
            \Log::error('Error fetching roles: ' . $e->getMessage());
                
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch roles: ' . $e->getMessage()
                ], 500);
            }
                
            return back()->with('error', 'Failed to fetch roles: ' . $e->getMessage());
        }
    }


    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'role_name' => 'required|string|max:255|unique:super_roles,name',
                'description' => 'nullable|string',
                'is_active' => 'required|boolean',
                'permissions' => 'required|array',
                'permissions.*' => 'string'
            ]);

            DB::beginTransaction();

            $role = SuperRole::create([
                'name' => $validated['role_name'],
                'display_name' => $validated['role_name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'],
                'is_system' => false
            ]);

            $permissionIds = [];
            foreach ($validated['permissions'] as $permissionString) {
                list($module, $action) = explode('.', $permissionString);
                $permission = SuperPermission::firstOrCreate(
                    ['name' => $action . '_' . $module],
                    [
                        'display_name' => ucfirst($action) . ' ' . ucfirst($module),
                        'module' => $module
                    ]
                );
                $permissionIds[] = $permission->id;
            }

            $role->permissions()->sync($permissionIds);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'role' => $role->load('permissions')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Display the specified role.
     */
    public function show(SuperRole $role)
    {
        return response()->json($role->load('permissions'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, SuperRole $role)
    {
        if ($role->is_system) {
            return response()->json([
                'message' => 'System roles cannot be modified'
            ], 403);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('super_roles', 'name')->ignore($role->id)
            ],
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:super_permissions,id',
        ]);

        DB::beginTransaction();
        try {
            // Update the role
            $role->update([
                'name' => $validated['name'],
                'display_name' => $validated['name'], // Use the same name for display_name
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active']
            ]);

            // Sync permissions
            $role->permissions()->sync($validated['permissions']);

            DB::commit();
            
            return response()->json([
                'message' => 'Role updated successfully',
                'role' => $role->load('permissions')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(SuperRole $role)
    {
        if ($role->is_system) {
            return response()->json([
                'message' => 'System roles cannot be deleted'
            ], 403);
        }

        DB::beginTransaction();
        try {
            // Remove all permissions first
            $role->permissions()->detach();
            
            // Then delete the role
            $role->delete();

            DB::commit();
            return response()->json([
                'message' => 'Role deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all permissions grouped by their group name.
     */
    public function permissions()
    {
        $permissions = SuperPermission::orderBy('group')
            ->orderBy('display_name')
            ->get()
            ->groupBy('group');

        return response()->json($permissions);
    }

    /**
     * Sync permissions for a role.
     */
    public function syncPermissions(Request $request, SuperRole $role)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:super_permissions,id',
        ]);

        try {
            $role->permissions()->sync($validated['permissions']);

            return response()->json([
                'message' => 'Permissions updated successfully',
                'role' => $role->load('permissions')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update permissions',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}