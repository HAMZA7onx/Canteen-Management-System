<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\MealComponent;
use Illuminate\Http\Request;

class MealComponentController extends Controller
{
    public function index()
    {
        $mealComponents = MealComponent::with('mealMenu')->get();
        return response()->json($mealComponents);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'meal_menu_id' => 'required|exists:meal_menus,id',
            'component_name' => 'required|max:255',
            'menu_name' => 'required|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric',
            'is_required' => 'required|boolean',
        ]);

        $mealComponent = MealComponent::create($validatedData);
        return response()->json($mealComponent, 201);
    }

    public function show($id)
    {
        $mealComponent = MealComponent::with('mealMenu')->findOrFail($id);
        return response()->json($mealComponent);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'meal_menu_id' => 'required|exists:meal_menus,id',
            'component_name' => 'required|max:255',
            'menu_name' => 'required|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric',
            'is_required' => 'required|boolean',
        ]);

        $mealComponent = MealComponent::findOrFail($id);
        $mealComponent->update($validatedData);
        return response()->json($mealComponent);
    }

    public function destroy($id)
    {
        $mealComponent = MealComponent::findOrFail($id);
        $mealComponent->delete();
        return response()->json(['message' => 'Meal component deleted successfully']);
    }
}
