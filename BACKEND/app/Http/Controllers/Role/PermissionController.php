<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    /**
     * Store a newly created permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $permission = Permission::create(['name' => $request->name, 'guard_name' => 'sanctum']);
        return response()->json($permission);
    }

    /**
     * Display the specified permission.
     *
     * @param  \Spatie\Permission\Models\Permission  $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Permission $permission)
    {
        return response()->json($permission);
    }

    /**
     * Update the specified permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Permission\Models\Permission  $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Permission $permission)
    {
        $permission->update(['name' => $request->name]);
        return response()->json($permission);
    }

    /**
     * Remove the specified permission from storage.
     *
     * @param  \Spatie\Permission\Models\Permission  $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            DB::table('permissions')->where('id', $id)->delete();
            return response()->json(['message' => 'Permission deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete permission'], 500);
        }
    }

    public function refreshPermissions(Request $request)
    {
        $user = $request->user();
        \Log::info('user: ', ['user' => $user]);
        $permissions = $user->getAllPermissions()->pluck('name');
        return response()->json(['permissions' => $permissions]);
    }

}
