<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\MealRecord;
use Illuminate\Http\Request;

class MealRecordController extends Controller
{
    public function index()
    {
        $mealRecords = MealRecord::with('badge', 'mealSchedule.mealMenu.mealCategory', 'mealSchedule.mealMenu.mealComponents')->get();
        return response()->json($mealRecords);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'badge_id' => 'required|exists:badges,id',
            'meal_schedule_id' => 'required|exists:meal_schedules,id',
            'price_paid' => 'required|numeric',
            'selected_components' => 'nullable|array',
            'taken_at' => 'required|date',
        ]);

        $mealRecord = MealRecord::create($validatedData);
        return response()->json($mealRecord, 201);
    }

    public function show($id)
    {
        $mealRecord = MealRecord::with('badge', 'mealSchedule.mealMenu.mealCategory', 'mealSchedule.mealMenu.mealComponents')->findOrFail($id);
        return response()->json($mealRecord);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'badge_id' => 'required|exists:badges,id',
            'meal_schedule_id' => 'required|exists:meal_schedules,id',
            'price_paid' => 'required|numeric',
            'selected_components' => 'nullable|array',
            'taken_at' => 'required|date',
        ]);

        $mealRecord = MealRecord::findOrFail($id);
        $mealRecord->update($validatedData);
        return response()->json($mealRecord);
    }

    public function destroy($id)
    {
        $mealRecord = MealRecord::findOrFail($id);
        $mealRecord->delete();
        return response()->json(['message' => 'Meal record deleted successfully']);
    }
}
