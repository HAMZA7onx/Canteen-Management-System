<?php

namespace App\Http\Controllers\WeekSchedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyMeal;
use App\Models\WeekSchedule;

class DailyMealController extends Controller
{
    public function index()
    {
        $dailyMeals = DailyMeal::with(['menus', 'mondaySchedules', 'tuesdaySchedules', 'wednesdaySchedules', 'thursdaySchedules', 'fridaySchedules', 'saturdaySchedules', 'sundaySchedules'])->get();
        return response()->json($dailyMeals);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
        ]);

        $dailyMeal = DailyMeal::create($validatedData);

        return response()->json($dailyMeal, 201);
    }

    public function show(DailyMeal $dailyMeal)
    {
        $dailyMeal->load(['menus', 'mondaySchedules', 'tuesdaySchedules', 'wednesdaySchedules', 'thursdaySchedules', 'fridaySchedules', 'saturdaySchedules', 'sundaySchedules']);
        return response()->json($dailyMeal);
    }

    public function update(Request $request, DailyMeal $dailyMeal)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
        ]);

        $dailyMeal->update($validatedData);

        return response()->json($dailyMeal);
    }

    public function destroy(DailyMeal $dailyMeal)
    {
        $dailyMeal->delete();

        return response()->json(null, 204);
    }

    public function attachWeekSchedule(Request $request, DailyMeal $dailyMeal, $day)
    {
        $validatedData = $request->validate([
            'week_schedule_id' => 'required|exists:week_schedule,id',
        ]);

        $weekSchedule = WeekSchedule::findOrFail($validatedData['week_schedule_id']);
        $dailyMeal->{"${day}Schedules"}()->attach($weekSchedule);

        return response()->json(['message' => 'Week schedule attached to the daily meal for ' . $day]);
    }

    public function detachWeekSchedule(DailyMeal $dailyMeal, WeekSchedule $weekSchedule, $day)
    {
        $dailyMeal->{"${day}Schedules"}()->detach($weekSchedule);

        return response()->json(['message' => 'Week schedule detached from the daily meal for ' . $day]);
    }
}
