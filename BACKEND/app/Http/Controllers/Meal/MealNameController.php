<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
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
        $mealName = MealName::create($request->all());
        return response()->json($mealName, 201);
    }

    public function show($id)
    {
        $mealName = MealName::findOrFail($id);
        return response()->json($mealName);
    }

    public function update(Request $request, $id)
    {
        $mealName = MealName::findOrFail($id);
        $mealName->update($request->all());
        return response()->json($mealName);
    }

    public function destroy($id)
    {
        $mealName = MealName::findOrFail($id);
        $mealName->delete();
        return response()->json(null, 204);
    }
}
