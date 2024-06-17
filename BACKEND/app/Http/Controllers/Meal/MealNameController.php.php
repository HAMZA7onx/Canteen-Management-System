<?php

namespace App\Http\Controllers\Meal;

use App\Models\MealName;
use Illuminate\Http\Request;

class MealNameController extends Controller
{
    public function index()
    {
        $mealNames = MealName::all();
        return response()->json($mealNames);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $mealName = MealName::create($validatedData);

        return response()->json($mealName, 201);
    }

    public function show($id)
    {
        $mealName = MealName::findOrFail($id);
        return response()->json($mealName);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $mealName = MealName::findOrFail($id);
        $mealName->update($validatedData);

        return response()->json($mealName);
    }

    public function destroy($id)
    {
        $mealName = MealName::findOrFail($id);
        $mealName->delete();

        return response()->json(null, 204);
    }
}
