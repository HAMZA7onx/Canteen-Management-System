<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meal;

class MealController extends Controller
{
    public function index()
    {
        $meals = Meal::with('mealRecords')->get();
        return response()->json($meals);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|in:breakfast,lunch,dinner,fotour,so7our',
            'mode' => 'required|in:normal,ramadan',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $meal = Meal::create($validatedData);

        return response()->json($meal, 201);
    }

    public function show(Meal $meal)
    {
        $meal->load('mealRecords');
        return response()->json($meal);
    }

    public function update(Request $request, Meal $meal)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|in:breakfast,lunch,dinner,fotour,so7our',
            'mode' => 'sometimes|in:normal,ramadan',
            'price' => 'sometimes|integer',
            'quantity' => 'sometimes|integer',
            'description' => 'sometimes|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
        ]);

        $meal->update($validatedData);

        return response()->json($meal);
    }

    public function destroy(Meal $meal)
    {
        $meal->delete();
        return response()->json(['message' => 'Meal deleted successfully']);
    }
}
