<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // Roles
    public function index()
    {
        $roles = Role::orderBy('updated_at', 'desc')->get();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $role = Role::create(['name' => $request->name, 'guard_name' => 'sanctum']);
        return response()->json($role);
    }

    public function show(Role $role)
    {
        return response()->json($role);
    }

    public function update(Request $request, Role $role)
    {
        $role->update(['name' => $request->name]);
        return response()->json($role);
    }

    public function destroy(Role $role)
    {
        $roleId = $role->id;
        $deleteRole = DB::delete("DELETE FROM roles WHERE id = ?", [$roleId]);

        if ($deleteRole) {
            return response()->json(['message' => 'Role deleted successfully']);
        } else {
            return response()->json(['message' => 'An issue occurred while deleting the role']);
        }
    }

    // Permissions
    public function indexPermissions()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    public function storePermission(Request $request)
    {
        $permission = Permission::create(['name' => $request->name, 'guard_name' => 'sanctum']);
        return response()->json($permission);
    }

    public function showPermission(Permission $permission)
    {
        return response()->json($permission);
    }

    public function updatePermission(Request $request, Permission $permission)
    {
        $permission->update(['name' => $request->name]);
        return response()->json($permission);
    }

    public function destroyPermission(Permission $permission)
    {
        $permissionId = $permission->id;
        $deletePermission = DB::delete("DELETE FROM permissions WHERE id = ?", [$permissionId]);

        if ($deletePermission) {
            return response()->json(['message' => 'Permission deleted successfully']);
        } else {
            return response()->json(['message' => 'An issue occurred while deleting the permission']);
        }
    }

    /**
     * Get the permissions assigned to a role.
     */
    public function getRolePermissions(Role $role)
    {
        $permissions = $role->permissions;

        return response()->json($permissions);
    }

    public function assignPermissions(Request $request, Role $role)
    {
        $permissionIds = $request->input('permissionIds');
        $permissions = Permission::whereIn('id', $permissionIds)->get();
        $role->givePermissionTo($permissions);
        return response()->json(['message' => 'Permissions assigned to role successfully']);
    }


    public function removePermission(Role $role, $permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $role->revokePermissionTo($permission);

        return response()->json(['message' => 'Permission removed from role successfully']);
    }
}
