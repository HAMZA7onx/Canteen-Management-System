<?php

namespace App\Http\Controllers\WeekSchedule;

use App\Http\Controllers\Controller;
use App\Models\WeekSchedule;
use App\Models\DailyMeal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WeekScheduleController extends Controller
{
    public function index()
    {
        $weekSchedules = WeekSchedule::with([
            'mondayDailyMeals.menus.foodComposants',
            'tuesdayDailyMeals.menus.foodComposants',
            'wednesdayDailyMeals.menus.foodComposants',
            'thursdayDailyMeals.menus.foodComposants',
            'fridayDailyMeals.menus.foodComposants',
            'saturdayDailyMeals.menus.foodComposants',
            'sundayDailyMeals.menus.foodComposants',
        ])->get();

        return response()->json($weekSchedules);
    }

    public function show(WeekSchedule $weekSchedule)
    {
        $weekSchedule->load([
            'mondayDailyMeals.menus.foodComposants',
            'tuesdayDailyMeals.menus.foodComposants',
            'wednesdayDailyMeals.menus.foodComposants',
            'thursdayDailyMeals.menus.foodComposants',
            'fridayDailyMeals.menus.foodComposants',
            'saturdayDailyMeals.menus.foodComposants',
            'sundayDailyMeals.menus.foodComposants',
        ]);

        return response()->json($weekSchedule);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'mode_name' => 'required',
            'description' => 'nullable',
            'status' => 'required|in:active,inactive',
            'creator' => 'required',
            'editors' => 'required|json',
        ]);

        $weekSchedule = WeekSchedule::create($validatedData);

        return response()->json($weekSchedule, 201);
    }

    public function update(Request $request, WeekSchedule $weekSchedule)
    {
        $validatedData = $request->validate([
            'mode_name' => 'min:5',
            'description' => 'nullable',
            'status' => 'in:active,inactive',
            'editors' => 'json',
        ]);

        $weekSchedule->update($validatedData);

        return response()->json($weekSchedule);
    }

    public function destroy(WeekSchedule $weekSchedule)
    {
        $weekSchedule->delete();
        return response()->json(null, 204);
    }

    public function attachDailyMeal(Request $request, $weekScheduleId, $day)
    {
        $validatedData = $request->validate([
            'daily_meal_id' => 'required|exists:daily_meals,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'price' => 'required|numeric',
        ]);

        $dailyMeal = DailyMeal::findOrFail($validatedData['daily_meal_id']);
        $weekSchedule = WeekSchedule::findOrFail($weekScheduleId);

        // Check if the daily meal is already attached to the same day
        $existingDailyMeals = $weekSchedule->{"${day}DailyMeals"}()->get();
        foreach ($existingDailyMeals as $existingDailyMeal) {
            if ($existingDailyMeal->id === $dailyMeal->id) {
                return response()->json(['error' => 'The daily meal is already attached to ' . $day], 400);
            }
        }

        // Check if the duration overlaps with any existing daily meal for the same day
        foreach ($existingDailyMeals as $existingDailyMeal) {
            $existingStartTime = Carbon::parse($existingDailyMeal->pivot->start_time);
            $existingEndTime = Carbon::parse($existingDailyMeal->pivot->end_time);
            $newStartTime = Carbon::parse($validatedData['start_time']);
            $newEndTime = Carbon::parse($validatedData['end_time']);

            if (
                ($newStartTime->between($existingStartTime, $existingEndTime) || $newEndTime->between($existingStartTime, $existingEndTime)) ||
                ($existingStartTime->between($newStartTime, $newEndTime) || $existingEndTime->between($newStartTime, $newEndTime))
            ) {
                return response()->json(['error' => 'The specified duration overlaps with an existing daily meal for ' . $day], 400);
            }
        }

        $weekSchedule->{"${day}DailyMeals"}()->attach($dailyMeal, [
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'price' => $validatedData['price'],
        ]);

        return response()->json(['message' => 'Daily meal attached to the week schedule for ' . $day]);
    }

    public function detachDailyMeal(WeekSchedule $weekSchedule, DailyMeal $dailyMeal, $day)
    {
        $weekSchedule->{"${day}DailyMeals"}()->detach($dailyMeal);
        return response()->json(['message' => 'Daily meal detached from the week schedule for ' . $day]);
    }
}
