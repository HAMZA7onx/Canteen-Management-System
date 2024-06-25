<?php

namespace App\Http\Controllers\WeekSchedule;

use App\Http\Controllers\Controller;
use App\Models\WeekSchedule;
use Illuminate\Http\Request;

class WeekScheduleController extends Controller
{
    public function index()
    {
        $weekSchedules = WeekSchedule::with(['mondayMeals', 'tuesdayMeals', 'wednesdayMeals', 'thursdayMeals', 'fridayMeals', 'saturdayMeals', 'sundayMeals'])->get();
        return response()->json($weekSchedules);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'mode_name' => 'required',
            'description' => 'nullable',
            'status' => 'required|in:active,inactive',
            'editor' => 'required',
        ]);

        $weekSchedule = WeekSchedule::create($validatedData);

        return response()->json($weekSchedule, 201);
    }

    public function show(WeekSchedule $weekSchedule)
    {
        $weekSchedule->load(['mondayMeals', 'tuesdayMeals', 'wednesdayMeals', 'thursdayMeals', 'fridayMeals', 'saturdayMeals', 'sundayMeals']);
        return response()->json($weekSchedule);
    }

    public function update(Request $request, WeekSchedule $weekSchedule)
    {
        $validatedData = $request->validate([
            'mode_name' => 'required',
            'description' => 'nullable',
            'status' => 'required|in:active,inactive',
            'editor' => 'required',
        ]);

        $weekSchedule->update($validatedData);

        return response()->json($weekSchedule);
    }

    public function destroy(WeekSchedule $weekSchedule)
    {
        $weekSchedule->delete();

        return response()->json(null, 204);
    }
}
