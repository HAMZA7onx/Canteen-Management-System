<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rfid' => 'required|unique:users',
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'nullable',
            'gender' => 'nullable|in:female,male',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'rfid' => $request->rfid,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
        ]);

        $status = "success";
        $response = ['user' => $user, 'status' => $status];
        return response()->json($response);
    }

    function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'password' => 'string|min:8',
            'phone_number' => 'nullable',
            'gender' => 'nullable|in:female,male',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($request->id);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('phone_number')) {
            $user->phone_number = $request->phone_number;
        }

        if ($request->has('gender')) {
            $user->gender = $request->gender;
        }

        $user->save();
        return response()->json(['status' => 'success']);
    }

    function destroy(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function getUserDetails($id)
    {
        $user = User::where('id', $id)->get()->first();
        return response()->json(['user' => $user], 200);
    }

    public function getUserRolesNames($id)
    {
        $user = User::find($id);
        if ($user) {
            $roles = $user->roles()->select('roles.id as role_id', 'roles.name')->get();
            return response()->json($roles);
        }
        return response()->json(['failed' => 'user id does not exist']);
    }
}
