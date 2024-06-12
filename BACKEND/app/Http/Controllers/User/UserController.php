<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function index()
    {
        $users = User::with('category')->get();
        return response()->json($users);
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:user_category,id',
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'phone_number' => 'nullable',
            'gender' => 'nullable|in:female,male',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'api_token' => str_replace('-', '', substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 20)),
        ]);

        $status = "success";
        $response = ['user' => $user, 'status' => $status];
        return response()->json($response);
    }

    function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'exists:user_category,id',
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $id,
            'phone_number' => 'nullable',
            'gender' => 'nullable|in:female,male',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($id);
        $user->category_id = $request->has('category_id') ? $request->category_id : $user->category_id;
        $user->name = $request->has('name') ? $request->name : $user->name;
        $user->email = $request->has('email') ? $request->email : $user->email;
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
}
