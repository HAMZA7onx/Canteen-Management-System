<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRolePermissionController extends Controller
{
    /**
     * Assign a role to a user.
     */
    public function assignRole(User $user, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $user->assignRole($role);

        return response()->json(['message' => 'Role assigned successfully']);
    }

    /**
     * Remove a role from a user.
     */
    public function removeRole(User $user, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $user->removeRole($role);

        return response()->json(['message' => 'Role removed successfully']);
    }

    /**
     * Assign a permission to a user.
     */
    public function assignPermission(User $user, $permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $user->givePermissionTo($permission);

        return response()->json(['message' => 'Permission assigned successfully']);
    }

    /**
     * Remove a permission from a user.
     */
    public function removePermission(User $user, $permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $user->revokePermissionTo($permission);

        return response()->json(['message' => 'Permission removed successfully']);
    }


    /**
     * Get the roles assigned to a user.
     */
    public function getUserRoles(User $user)
    {
        $roles = $user->roles;

        return response()->json($roles);
    }

    /**
     * Get the permissions assigned to a user.
     */
    public function getUserPermissions(User $user)
    {
        $permissions = $user->permissions;

        return response()->json($permissions);
    }
}
