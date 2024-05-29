<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserCategory;
use Illuminate\Http\Request;

class UserCategoryController extends Controller
{
    public function index()
    {
        $userCategories = UserCategory::all();
        return response()->json($userCategories);
    }

    public function store(Request $request)
    {
        $userCategory = UserCategory::create($request->all());
        return response()->json($userCategory, 201);
    }

    public function show($id)
    {
        $userCategory = UserCategory::findOrFail($id);
        return response()->json($userCategory);
    }

    public function update(Request $request, $id)
    {
        $userCategory = UserCategory::findOrFail($id);
        $userCategory->update($request->all());
        return response()->json($userCategory);
    }

    public function destroy($id)
    {
        $userCategory = UserCategory::findOrFail($id);
        $userCategory->delete();
        return response()->json(null, 204);
    }
}
