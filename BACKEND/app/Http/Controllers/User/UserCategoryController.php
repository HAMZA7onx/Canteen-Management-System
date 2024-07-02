<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCategoryController extends Controller
{
    public function index()
    {
        $userCategories = UserCategory::all();
        return response()->json($userCategories);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $userCategory = UserCategory::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'creator' => $user->email,
            'editors' => []
        ]);

        return response()->json($userCategory, 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $userCategory = UserCategory::findOrFail($id);

        $userCategory->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        // Add the current user's email to the editors array if not already present
        if (!in_array($user->email, $userCategory->editors)) {
            $userCategory->editors = array_merge($userCategory->editors, [$user->email]);
            $userCategory->save();
        }

        return response()->json($userCategory);
    }

    public function show($id)
    {
        $userCategory = UserCategory::findOrFail($id);
        return response()->json($userCategory);
    }

    public function destroy($id)
    {
        $userCategory = UserCategory::findOrFail($id);
        $userCategory->delete();
        return response()->json(null, 204);
    }
}
