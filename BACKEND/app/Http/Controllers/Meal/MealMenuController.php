<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\MealMenu;
use Illuminate\Http\Request;

class MealMenuController extends Controller
{
    public function index()
    {
        $mealMenus = MealMenu::with('mealCategory')->get();
        return response()->json($mealMenus);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'meal_category_id' => 'required|exists:meal_categories,id',
            'menu_name' => 'required|max:255',
            'price' => 'required|numeric',
        ]);

        $mealMenu = MealMenu::create($validatedData);
        return response()->json($mealMenu, 201);
    }

    public function show($id)
    {
        $mealMenu = MealMenu::with('mealCategory', 'mealComponents')->findOrFail($id);
        return response()->json($mealMenu);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'meal_category_id' => 'required|exists:meal_categories,id',
            'menu_name' => 'required|max:255',
            'price' => 'required|numeric',
        ]);

        $mealMenu = MealMenu::findOrFail($id);
        $mealMenu->update($validatedData);
        return response()->json($mealMenu);
    }

    public function destroy($id)
    {
        $mealMenu = MealMenu::findOrFail($id);
        $mealMenu->delete();
        return response()->json(['message' => 'Meal menu deleted successfully']);
    }
}
