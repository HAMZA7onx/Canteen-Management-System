<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function index()
    {
        $users = User::with('category')->latest()->get();
        return response()->json($users);
    }


//    public function store(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'category_id' => 'required|exists:user_category,id',
//            'name' => 'required|max:255',
//            'email' => 'required|email|unique:users',
//            'phone_number' => 'nullable',
//            'gender' => 'nullable|in:female,male',
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json(['errors' => $validator->errors()], 422);
//        }
//
//        $currentUser = Auth::user();
//
//        $user = User::create([
//            'category_id' => $request->category_id,
//            'name' => $request->name,
//            'email' => $request->email,
//            'phone_number' => $request->phone_number,
//            'gender' => $request->gender,
//            'api_token' => str_replace('-', '', substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 20)),
//            'creator' => $currentUser->email,
//            'editors' => json_encode([$currentUser->email]),
//            'affected_categories' => UserCategory::where('id', $request->category_id)->get()->name
//        ]);
//
//        $user->load('category');
//
//        $status = "success";
//        $response = ['user' => $user, 'status' => $status];
//        return response()->json($response);
//    }
//
//    public function update(Request $request, $id)
//    {
//        $validator = Validator::make($request->all(), [
//            'category_id' => 'exists:user_category,id',
//            'name' => 'string|max:255',
//            'email' => 'email|unique:users,email,' . $id,
//            'phone_number' => 'nullable',
//            'gender' => 'nullable|in:female,male',
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json(['errors' => $validator->errors()], 422);
//        }
//
//        $user = User::findOrFail($id);
//        $currentUser = Auth::user();
//
//        $user->category_id = $request->has('category_id') ? $request->category_id : $user->category_id;
//        $user->name = $request->has('name') ? $request->name : $user->name;
//        $user->email = $request->has('email') ? $request->email : $user->email;
//        $user->phone_number = $request->has('phone_number') ? $request->phone_number : $user->phone_number;
//        $user->gender = $request->has('gender') ? $request->gender : $user->gender;
//
//        // Update editors
//        $editors = json_decode($user->editors, true) ?: [];
//        if (!in_array($currentUser->email, $editors)) {
//            $editors[] = $currentUser->email;
//            $user->editors = json_encode($editors);
//        }
//
//        $user->save();
//
//        $user->load('category');
//
//        $status = "success";
//        $response = ['user' => $user, 'status' => $status];
//        return response()->json($response);
//    }
    public function store(Request $request)
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

        $currentUser = Auth::user();

        // Get the category name
        $category = UserCategory::findOrFail($request->category_id);
        $affectedCategories = [$category->name];

        $user = User::create([
            'category_id' => $request->category_id,
            'affected_categories' => json_encode($affectedCategories),
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'status' => 'assigned',
            'api_token' => str_replace('-', '', substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 20)),
            'creator' => $currentUser->email,
            'editors' => json_encode([]),
        ]);

        $user->load('category');

        $status = "success";
        $response = ['user' => $user, 'status' => $status];
        return response()->json($response);
    }

    public function update(Request $request, $id)
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
        $currentUser = Auth::user();

        if ($request->has('category_id')) {
            $user->category_id = $request->category_id;

            // Update affected_categories with the new category name
            $category = UserCategory::findOrFail($request->category_id);
            $affectedCategories = json_decode($user->affected_categories, true) ?: [];
            $affectedCategories[] = $category->name;
            $user->affected_categories = json_encode(array_unique($affectedCategories));
        }

        $user->name = $request->has('name') ? $request->name : $user->name;
        $user->email = $request->has('email') ? $request->email : $user->email;
        $user->phone_number = $request->has('phone_number') ? $request->phone_number : $user->phone_number;
        $user->gender = $request->has('gender') ? $request->gender : $user->gender;

        // Update editors
        $editors = json_decode($user->editors, true) ?: [];
        if (!in_array($currentUser->email, $editors)) {
            $editors[] = $currentUser->email;
            $user->editors = json_encode($editors);
        }

        $user->save();

        $user->load('category');

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
