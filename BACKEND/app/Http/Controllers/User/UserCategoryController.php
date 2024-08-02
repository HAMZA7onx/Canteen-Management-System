<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class UserCategoryController extends Controller
{
    public function index()
    {
        $userCategories = UserCategory::orderBy('updated_at', 'desc')->get();

        $userCategories = $userCategories->map(function ($category) {
            // Check if this category is associated with any users
            $isAssigned = $category->users()->exists();

            $category->is_assigned = $isAssigned;
            return $category;
        });

        return response()->json($userCategories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:user_category,name|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = Auth::user();
            $userCategory = UserCategory::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'creator' => $user->email,
                'editors' => []
            ]);

            return response()->json($userCategory, 201);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error occurred.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:user_category,name,' . $id . '|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
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
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User category not found.'], 404);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error occurred.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
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
