<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\MealMode;
use Illuminate\Http\Request;

class MealModeController extends Controller
{
    public function index()
    {
        $mealModes = MealMode::all();
        return response()->json($mealModes);
    }

    public function store(Request $request)
    {
        $mealMode = MealMode::create($request->all());
        return response()->json($mealMode, 201);
    }

    public function show($id)
    {
        $mealMode = MealMode::findOrFail($id);
        return response()->json($mealMode);
    }

    public function update(Request $request, $id)
    {
        $mealMode = MealMode::findOrFail($id);
        $mealMode->update($request->all());
        return response()->json($mealMode);
    }

    public function destroy($id)
    {
        $mealMode = MealMode::findOrFail($id);
        $mealMode->delete();
        return response()->json(null, 204);
    }
}
