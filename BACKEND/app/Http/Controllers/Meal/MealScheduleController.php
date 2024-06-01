<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\MealSchedule;
use Illuminate\Http\Request;

class MealScheduleController extends Controller
{
    public function index()
    {
        $mealSchedules = MealSchedule::all();
        return response()->json($mealSchedules);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'meal_menu_id' => 'required|exists:meal_menus,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $mealSchedule = MealSchedule::create($validatedData);
        return response()->json($mealSchedule, 201);
    }

    public function show($id)
    {
        $mealSchedule = MealSchedule::findOrFail($id);
        return response()->json($mealSchedule);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'meal_menu_id' => 'required|exists:meal_menus,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $mealSchedule = MealSchedule::findOrFail($id);
        $mealSchedule->update($validatedData);
        return response()->json($mealSchedule);
    }

    public function destroy($id)
    {
        $mealSchedule = MealSchedule::findOrFail($id);
        $mealSchedule->delete();
        return response()->json(['message' => 'Meal schedule deleted successfully']);
    }
}
