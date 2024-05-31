<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\MealCategory;
use Illuminate\Http\Request;

class MealCategoryController extends Controller
{
    public function index()
    {
        $mealCategories = MealCategory::all();
        return response()->json($mealCategories);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:meal_categories|max:255',
        ]);

        $mealCategory = MealCategory::create($validatedData);
        return response()->json($mealCategory, 201);
    }

    public function show($id)
    {
        $mealCategory = MealCategory::findOrFail($id);
        return response()->json($mealCategory);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:meal_categories,name,' . $id . '|max:255',
        ]);

        $mealCategory = MealCategory::findOrFail($id);
        $mealCategory->update($validatedData);
        return response()->json($mealCategory);
    }

    public function destroy($id)
    {
        $mealCategory = MealCategory::findOrFail($id);
        $mealCategory->delete();
        return response()->json(['message' => 'Meal category deleted successfully']);
    }
}
