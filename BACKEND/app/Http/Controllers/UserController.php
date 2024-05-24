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

    function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rfid' => 'required|unique:users,rfid,' . $id,
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $id,
            'password' => 'string|min:8',
            'phone_number' => 'nullable',
            'gender' => 'nullable|in:female,male',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($id);

        $user->rfid = $request->has('rfid') ? $request->rfid : $user->rfid;
        $user->name = $request->has('name') ? $request->name : $user->name;
        $user->email = $request->has('email') ? $request->email : $user->email;
        $user->password = $request->has('password') ? Hash::make($request->password) : $user->password;
        $user->phone_number = $request->has('phone_number') ? $request->phone_number : $user->phone_number;
        $user->gender = $request->has('gender') ? $request->gender : $user->gender;

        $user->save();

        $status = "success";
        $response = ['user' => $user, 'status' => $status];
        return response()->json($response);
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
