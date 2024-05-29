<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public function index()
    {
        $meals = Meal::all();
        return response()->json($meals);
    }

    public function store(Request $request)
    {
        $meal = Meal::create($request->all());
        return response()->json($meal, 201);
    }

    public function show($id)
    {
        $meal = Meal::findOrFail($id);
        return response()->json($meal);
    }

    public function update(Request $request, $id)
    {
        $meal = Meal::findOrFail($id);
        $meal->update($request->all());
        return response()->json($meal);
    }

    public function destroy($id)
    {
        $meal = Meal::findOrFail($id);
        $meal->delete();
        return response()->json(null, 204);
    }
}
