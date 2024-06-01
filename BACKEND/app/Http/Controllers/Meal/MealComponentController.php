<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\Component;
use Illuminate\Http\Request;

class MealComponentController extends Controller
{
    public function index()
    {
        $mealComponents = Component::all();
        return response()->json($mealComponents);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'component_name' => 'required|string',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric',
            'is_required' => 'required|boolean',
        ]);

        $mealComponent = Component::create($validatedData);
        return response()->json($mealComponent, 201);
    }

    public function show($id)
    {
        $mealComponent = Component::findOrFail($id);
        return response()->json($mealComponent);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'component_name' => 'required|string',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric',
            'is_required' => 'required|boolean',
        ]);

        $mealComponent = Component::findOrFail($id);
        $mealComponent->update($validatedData);
        return response()->json($mealComponent);
    }

    public function destroy($id)
    {
        $mealComponent = Component::findOrFail($id);
        $mealComponent->delete();
        return response()->json(['message' => 'Meal component deleted successfully']);
    }
}
