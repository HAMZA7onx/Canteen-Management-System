<?php

namespace App\Http\Controllers\WeekSchedule;

use App\Http\Controllers\Controller;
use App\Models\WeekSchedule;
use App\Models\DailyMeal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


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
        ]);

        $validatedData['creator'] = auth()->user()->email;
        $validatedData['editors'] = []; // Initialize the editors array as empty

        $weekSchedule = DB::transaction(function () use ($validatedData) {
            if ($validatedData['status'] === 'active') {
                WeekSchedule::where('status', 'active')->update(['status' => 'inactive']);
            }
            return WeekSchedule::create($validatedData);
        });

        return response()->json($weekSchedule, 201);
    }

    public function update(Request $request, WeekSchedule $weekSchedule)
    {
        $validatedData = $request->validate([
            'mode_name' => 'min:5',
            'description' => 'nullable',
            'status' => 'in:active,inactive',
        ]);

        // Get the existing editors array
        $editors = $weekSchedule->editors;
        // Get the authenticated user's email
        $authUserEmail = auth()->user()->email;
        // Check if the authenticated user's email already exists in the editors array
        if (!in_array($authUserEmail, $editors)) {
            // If not, add it to the editors array
            $editors[] = $authUserEmail;
        }

        // Update the editors array in the validated data
        $validatedData['editors'] = $editors;

        DB::transaction(function () use ($weekSchedule, $validatedData) {
            if ($validatedData['status'] === 'active' && $weekSchedule->status !== 'active') {
                WeekSchedule::where('status', 'active')->update(['status' => 'inactive']);
            }
            $weekSchedule->update($validatedData);
        });

        return response()->json($weekSchedule);
    }

    public function destroy(WeekSchedule $weekSchedule)
    {
        $weekSchedule->delete();
        return response()->json(null, 204);
    }

    public function attachDailyMeal(Request $request, $weekScheduleId, $day)
    {
        \Log::info("Attaching daily meal for day: $day, weekScheduleId: $weekScheduleId");
        \Log::info("Request data: " . json_encode($request->all()));

        $validatedData = $request->validate([
            'daily_meal_id' => 'required|exists:daily_meals,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'price' => 'required|numeric',
            'discounts' => 'required|array',
            'discounts.*' => 'numeric|min:0|max:100',
        ]);

        \Log::info("Validated data: " . json_encode($validatedData));

        $dailyMeal = DailyMeal::findOrFail($validatedData['daily_meal_id']);
        $weekSchedule = WeekSchedule::findOrFail($weekScheduleId);

        \Log::info("Daily meal: " . json_encode($dailyMeal));
        \Log::info("Week schedule: " . json_encode($weekSchedule));

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

        DB::beginTransaction();
        try {
            $attachData = [
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'price' => $validatedData['price'],
                'created_at' => now('Europe/Paris'),
                'updated_at' => now('Europe/Paris')
            ];
            \Log::info("Attaching data: " . json_encode($attachData));

            $weekSchedule->{"${day}DailyMeals"}()->attach($dailyMeal, $attachData);

            $pivotId = DB::table("{$day}_daily_meal")
                ->where('week_schedule_id', $weekSchedule->id)
                ->where('daily_meal_id', $dailyMeal->id)
                ->value('id');

            \Log::info("Pivot ID: $pivotId");

            // Log the inserted record
            $insertedRecord = DB::table("{$day}_daily_meal")
                ->where('id', $pivotId)
                ->first();
            \Log::info("Inserted record: " . json_encode($insertedRecord));

            foreach ($validatedData['discounts'] as $categoryId => $discount) {
                DB::table("{$day}_discounts")->insert([
                    'meal_id' => $pivotId,
                    'category_id' => $categoryId,
                    'discount' => $discount,
                    'created_at' => now('Europe/Paris'),
                    'updated_at' => now('Europe/Paris'),
                ]);
            }

            DB::commit();
            \Log::info("Transaction committed successfully");
            return response()->json(['message' => 'Daily meal attached to the week schedule for ' . $day]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error attaching daily meal: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Failed to attach daily meal: ' . $e->getMessage()], 500);
        }
    }


    public function detachDailyMeal(WeekSchedule $weekSchedule, DailyMeal $dailyMeal, $day)
    {
        $weekSchedule->{"${day}DailyMeals"}()->detach($dailyMeal);
        return response()->json(['message' => 'Daily meal detached from the week schedule for ' . $day]);
    }

    public function getDailyMealDiscounts(WeekSchedule $weekSchedule, $day, DailyMeal $dailyMeal)
    {
        $pivotTable = "{$day}_daily_meal";
        $discountTable = "{$day}_discounts";

        $pivotId = DB::table($pivotTable)
            ->where('week_schedule_id', $weekSchedule->id)
            ->where('daily_meal_id', $dailyMeal->id)
            ->value('id');

        if (!$pivotId) {
            return response()->json(['error' => 'Daily meal not found in the specified day'], 404);
        }

        $discounts = DB::table($discountTable)
            ->where('meal_id', $pivotId)
            ->join('user_category', 'user_category.id', '=', "{$discountTable}.category_id")
            ->select("{$discountTable}.category_id", "{$discountTable}.discount", 'user_category.name as category_name')
            ->get();

        $formattedDiscounts = $discounts->mapWithKeys(function ($item) {
            return [$item->category_id => [
                'discount' => $item->discount,
                'category_name' => $item->category_name
            ]];
        });

        return response()->json($formattedDiscounts);
    }
}
