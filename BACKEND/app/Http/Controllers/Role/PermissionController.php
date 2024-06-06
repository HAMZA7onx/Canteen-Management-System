<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return response()->json(['permissions' => $permissions]);
    }

    public function show(Request $request)
    {
        $permission = Permission::findOrFail($request->permission);
        return response()->json(['permission' => $permission]);
    }

    public function store(Request $request)
    {
        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'sanctum',
        ]);

        return response()->json(['permission' => $permission]);
    }

    public function update(Request $request)
    {
        $permission = Permission::findOrFail($request->permission);

        $permission->update([
            'name' => $request->name,
            'guard_name' => 'sanctum',
        ]);

        return response()->json(['permission' => $permission]);
    }

    public function destroy(Request $request)
    {
        $permission = Permission::findOrFail($request->permission);

        $permission->delete();

        return response()->json(['message' => 'Permission deleted']);
    }
}
