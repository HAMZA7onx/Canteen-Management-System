<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rfid' => 'required|string|unique:users',
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'nullable|string',
            'gender' => 'nullable|in:male,female',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only('rfid', 'name', 'email', 'password', 'phone_number', 'gender');
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $response = [
            'status' => 'success',
            'message' => 'User is created successfully.',
            'data' => [
                'user' => $user,
            ],
        ];
        return response()->json($response);
    }

    function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            $response = [
                'status' => 'success',
                'message' => 'User is logged in successfully.',
                'data' => [
                    'user' => $user,
                    'token' => $token,
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
    function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            return response()->json(['message' => 'Logged out successfully']);
        } else {
            return response()->json(['error' => 'Not authenticated']);
        }
    }
}
