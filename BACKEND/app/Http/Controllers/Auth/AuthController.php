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
use App\Models\AdminBadge;
use App\Models\PosDevice;

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

    public function loginWithBadge(Request $request)
    {
        $clientIp = $request->ip();
        \Log::info("Client IP: " . $clientIp);
        $allowedDevice = PosDevice::where('ip_address', $clientIp)
            ->where('status', 'allowed')
            ->first();
        if (!$allowedDevice) {
            return response()->json([
                'status' => 'failed',
                'message' => 'This device is not authorized to access the badging system.'
            ], 403);
        }

        $badge = AdminBadge::where('rfid', $request->rfid)->first();
        if (!$badge || $badge->status !== 'assigned') {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid badge'
            ], 401);
        }

        $admin = $badge->admin;
        if (!$admin->hasPermissionTo('ouvrir_interface_de_pointage_sur_POS')) {
            return response()->json([
                'status' => 'failed',
                'message' => 'You do not have permission to access this device'
            ], 403);
        }

        $token = $admin->createToken('auth_token')->plainTextToken;
        $adminRoles = $admin->getRoleNames();
        $adminPermissions = $admin->getAllPermissions();
        return response()->json([
            'status' => 'success',
            'message' => 'Admin logged in successfully.',
            'data' => [
                'admin' => $admin,
                'token' => $token,
                'roles' => $adminRoles,
                'permissions' => $adminPermissions,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('sanctum')->check()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        } else {
            return response()->json(['error' => 'Not authenticated']);
        }
    }
}
