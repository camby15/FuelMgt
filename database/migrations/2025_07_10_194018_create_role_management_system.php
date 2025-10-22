<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create super_roles table
        Schema::create('super_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // Create super_permissions table
        Schema::create('super_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('group')->nullable();
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create super_role_permission pivot table
        Schema::create('super_role_permission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('super_roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('super_permissions')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['role_id', 'permission_id']);
        });

        // Create super_admin_role pivot table
        Schema::create('super_admin_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('super_admin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('super_roles')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['super_admin_id', 'role_id']);
        });

        // Create super_admin_permission pivot table
        Schema::create('super_admin_permission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('super_admin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('super_permissions')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['super_admin_id', 'permission_id']);
        });

        // Insert default roles and permissions
        $this->seedDefaultData();
    }

    public function down()
    {
        Schema::dropIfExists('super_admin_permission');
        Schema::dropIfExists('super_admin_role');
        Schema::dropIfExists('super_role_permission');
        Schema::dropIfExists('super_permissions');
        Schema::dropIfExists('super_roles');
    }

    /**
     * Seed default roles and permissions
     */
    protected function seedDefaultData()
    {
        // Insert default roles
        $adminRoleId = DB::table('super_roles')->insertGetId([
            'name' => 'super_admin',
            'display_name' => 'Super Administrator',
            'description' => 'Super administrator with full access to all features',
            'is_active' => true,
            'is_system' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $managerRoleId = DB::table('super_roles')->insertGetId([
            'name' => 'manager',
            'display_name' => 'Manager',
            'description' => 'Manager with limited administrative access',
            'is_active' => true,
            'is_system' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert default permissions
        $permissions = [
            // Super User Management
            ['name' => 'view_super_users', 'group' => 'super_users', 'display_name' => 'View Super Users', 'description' => 'Can view super users list'],
            ['name' => 'create_super_users', 'group' => 'super_users', 'display_name' => 'Create Super Users', 'description' => 'Can create new super users'],
            ['name' => 'edit_super_users', 'group' => 'super_users', 'display_name' => 'Edit Super Users', 'description' => 'Can edit existing super users'],
            ['name' => 'delete_super_users', 'group' => 'super_users', 'display_name' => 'Delete Super Users', 'description' => 'Can delete super users'],
            
            // Role Management
            ['name' => 'view_roles', 'group' => 'roles', 'display_name' => 'View Roles', 'description' => 'Can view roles list'],
            ['name' => 'create_roles', 'group' => 'roles', 'display_name' => 'Create Roles', 'description' => 'Can create new roles'],
            ['name' => 'edit_roles', 'group' => 'roles', 'display_name' => 'Edit Roles', 'description' => 'Can edit existing roles'],
            ['name' => 'delete_roles', 'group' => 'roles', 'display_name' => 'Delete Roles', 'description' => 'Can delete roles'],
            
            // System Settings
            ['name' => 'manage_system_settings', 'group' => 'settings', 'display_name' => 'Manage System Settings', 'description' => 'Can manage system-wide settings'],
            
            // Audit Logs
            ['name' => 'view_audit_logs', 'group' => 'audit', 'display_name' => 'View Audit Logs', 'description' => 'Can view system audit logs'],
        ];

        $permissionIds = [];
        foreach ($permissions as $permission) {
            $permissionIds[$permission['name']] = DB::table('super_permissions')->insertGetId(array_merge($permission, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Assign all permissions to super_admin role
        foreach ($permissionIds as $permissionId) {
            DB::table('super_role_permission')->insert([
                'role_id' => $adminRoleId,
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assign basic permissions to manager role
        $managerPermissions = ['view_super_users', 'view_roles', 'view_audit_logs'];
        foreach ($managerPermissions as $permission) {
            if (isset($permissionIds[$permission])) {
                DB::table('super_role_permission')->insert([
                    'role_id' => $managerRoleId,
                    'permission_id' => $permissionIds[$permission],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
};