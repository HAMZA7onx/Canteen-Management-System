<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:admins',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only('name', 'email', 'password');
        $data['password'] = Hash::make($data['password']);

        $admin = Admin::create($data);

        $response = [
            'status' => 'success',
            'message' => 'Admin is created successfully.',
            'data' => [
                'admin' => $admin,
            ],
        ];
        return response()->json($response);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $admin = Admin::where('email', $credentials['email'])->first();

        if ($admin && Hash::check($credentials['password'], $admin->password)) {
            $token = $admin->createToken('auth_token')->plainTextToken;
            $adminRoles = $admin->getRoleNames();
            $adminPermissions = $admin->getAllPermissions();
            $availableRoles = Role::all();
            $availablePermissions = Permission::all();

            $response = [
                'status' => 'success',
                'message' => 'Admin is logged in successfully.',
                'data' => [
                    'admin' => $admin,
                    'token' => $token,
                    'roles' => $adminRoles,
                    'permissions' => $adminPermissions,
                    'availableRoles' => $availableRoles,
                    'availablePermissions' => $availablePermissions
                ],
            ];
            return response()->json($response);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid credentials'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        } else {
            return response()->json(['error' => 'Not authenticated']);
        }
    }
}
