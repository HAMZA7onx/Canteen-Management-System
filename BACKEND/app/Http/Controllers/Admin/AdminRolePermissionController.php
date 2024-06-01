<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminRolePermissionController extends Controller
{
    /**
     * Assign a role to an admin.
     */
    public function assignRole(Admin $admin, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $admin->assignRole($role);

        return response()->json(['message' => 'Role assigned successfully']);
    }

    /**
     * Remove a role from an admin.
     */
    public function removeRole(Admin $admin, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $admin->removeRole($role);

        return response()->json(['message' => 'Role removed successfully']);
    }

    /**
     * Assign a permission to an admin.
     */
    public function assignPermission(Admin $admin, $permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $admin->givePermissionTo($permission);

        return response()->json(['message' => 'Permission assigned successfully']);
    }

    /**
     * Remove a permission from an admin.
     */
    public function removePermission(Admin $admin, $permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $admin->revokePermissionTo($permission);

        return response()->json(['message' => 'Permission removed successfully']);
    }


    /**
     * Get the roles assigned to an admin.
     */
    public function getAdminRoles(Admin $admin)
    {
        $roles = $admin->roles;

        return response()->json($roles);
    }

    /**
     * Get the permissions assigned to an admin.
     */
    public function getAdminPermissions(Admin $admin)
    {
        $permissions = $admin->permissions;

        return response()->json($permissions);
    }
}
