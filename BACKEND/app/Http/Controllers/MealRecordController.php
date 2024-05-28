<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MealRecord;

class MealRecordController extends Controller
{
    public function index()
    {
        $mealRecords = MealRecord::with('badge', 'meal')->get();
        return response()->json($mealRecords);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'badge_id' => 'required|exists:badges,id',
            'meal_id' => 'required|exists:meals,id',
            'taken_at' => 'required|date',
        ]);

        $mealRecord = MealRecord::create($validatedData);

        return response()->json($mealRecord, 201);
    }

    public function show(MealRecord $mealRecord)
    {
        $mealRecord->load('badge', 'meal');
        return response()->json($mealRecord);
    }

    public function update(Request $request, MealRecord $mealRecord)
    {
        $validatedData = $request->validate([
            'badge_id' => 'sometimes|exists:badges,id',
            'meal_id' => 'sometimes|exists:meals,id',
            'taken_at' => 'sometimes|date',
        ]);

        $mealRecord->update($validatedData);

        return response()->json($mealRecord);
    }

    public function destroy(MealRecord $mealRecord)
    {
        $mealRecord->delete();
        return response()->json(['message' => 'Meal record deleted successfully']);
    }
}
