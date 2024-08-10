<?php

namespace App\Http\Controllers\WeekSchedule;

use App\Http\Controllers\Controller;
use App\Models\WeekSchedule;
use App\Models\Menu;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WeekScheduleController extends Controller
{
    public function index()
    {
        $weekSchedules = WeekSchedule::with([
            'mondayMenus.foodComposants',
            'tuesdayMenus.foodComposants',
            'wednesdayMenus.foodComposants',
            'thursdayMenus.foodComposants',
            'fridayMenus.foodComposants',
            'saturdayMenus.foodComposants',
            'sundayMenus.foodComposants',
        ])
            ->orderBy('updated_at', 'desc')
            ->get();
        return response()->json($weekSchedules);
    }

    public function show(WeekSchedule $weekSchedule)
    {
        $weekSchedule->load([
            'mondayMenus.foodComposants',
            'tuesdayMenus.foodComposants',
            'wednesdayMenus.foodComposants',
            'thursdayMenus.foodComposants',
            'fridayMenus.foodComposants',
            'saturdayMenus.foodComposants',
            'sundayMenus.foodComposants',
        ]);
        return response()->json($weekSchedule);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'mode_name' => 'required|unique:week_schedule,mode_name',
            'description' => 'nullable',
            'status' => 'required|in:active,inactive',
        ]);

        $validatedData['creator'] = auth()->user()->email;
        $validatedData['editors'] = [];

        try {
            $weekSchedule = DB::transaction(function () use ($validatedData) {
                if ($validatedData['status'] === 'active') {
                    WeekSchedule::where('status', 'active')->update(['status' => 'inactive']);
                }
                return WeekSchedule::create($validatedData);
            });

            return response()->json($weekSchedule, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['error' => 'A week schedule with this mode name already exists.'], 422);
            }
            throw $e;
        }
    }

    public function update(Request $request, WeekSchedule $weekSchedule)
    {
        $validatedData = $request->validate([
            'mode_name' => 'min:5|unique:week_schedule,mode_name,' . $weekSchedule->id,
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

        try {
            DB::transaction(function () use ($weekSchedule, $validatedData) {
                if (isset($validatedData['status']) && $validatedData['status'] === 'active' && $weekSchedule->status !== 'active') {
                    WeekSchedule::where('status', 'active')->update(['status' => 'inactive']);
                }
                $weekSchedule->update($validatedData);
            });

            return response()->json($weekSchedule);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['error' => 'A week schedule with this mode name already exists.'], 422);
            }
            throw $e;
        }
    }

    public function destroy(WeekSchedule $weekSchedule)
    {
        $weekSchedule->delete();
        return response()->json(null, 204);
    }

    public function attachMenu(Request $request, $weekScheduleId, $day)
    {
        \Log::info("Attaching menu for day: $day, weekScheduleId: $weekScheduleId");
        \Log::info("Request data: " . json_encode($request->all()));

        $validatedData = $request->validate([
            'menu_id' => 'required|exists:menu,id',
            'meal_name' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'price' => 'required|numeric',
            'discounts' => 'required|array',
            'discounts.*' => 'numeric|min:0|max:100',
        ]);

        \Log::info("Validated data: " . json_encode($validatedData));

        $menu = Menu::findOrFail($validatedData['menu_id']);
        $weekSchedule = WeekSchedule::findOrFail($weekScheduleId);

        \Log::info("Menu: " . json_encode($menu));
        \Log::info("Week schedule: " . json_encode($weekSchedule));

        // Check if the menu is already attached to the same day
        $existingMenus = $weekSchedule->{"${day}Menus"}()->get();
        foreach ($existingMenus as $existingMenu) {
            if ($existingMenu->id === $menu->id) {
                return response()->json(['error' => 'The menu is already attached to ' . $day], 400);
            }
        }

        // Check if the duration overlaps with any existing menu for the same day
        foreach ($existingMenus as $existingMenu) {
            $existingStartTime = Carbon::parse($existingMenu->pivot->start_time);
            $existingEndTime = Carbon::parse($existingMenu->pivot->end_time);
            $newStartTime = Carbon::parse($validatedData['start_time']);
            $newEndTime = Carbon::parse($validatedData['end_time']);

            if (
                ($newStartTime->between($existingStartTime, $existingEndTime) || $newEndTime->between($existingStartTime, $existingEndTime)) ||
                ($existingStartTime->between($newStartTime, $newEndTime) || $existingEndTime->between($newStartTime, $newEndTime))
            ) {
                return response()->json(['error' => 'The specified duration overlaps with an existing menu for ' . $day], 400);
            }
        }

        DB::beginTransaction();
        try {
            $attachData = [
                'meal_name' => $validatedData['meal_name'],
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'price' => $validatedData['price'],
                'created_at' => now('Europe/Paris'),
                'updated_at' => now('Europe/Paris')
            ];
            \Log::info("Attaching data: " . json_encode($attachData));

            $weekSchedule->{"${day}Menus"}()->attach($menu, $attachData);

            $pivotId = DB::table("{$day}_daily_meal")
                ->where('week_schedule_id', $weekSchedule->id)
                ->where('menu_id', $menu->id)
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
            return response()->json(['message' => 'Menu attached to the week schedule for ' . $day]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error attaching menu: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Failed to attach menu: ' . $e->getMessage()], 500);
        }
    }

    public function detachMenu(WeekSchedule $weekSchedule, Menu $menu, $day)
    {
        $weekSchedule->{"${day}Menus"}()->detach($menu);
        return response()->json(['message' => 'Menu detached from the week schedule for ' . $day]);
    }

    public function getMenuDiscounts(WeekSchedule $weekSchedule, $day, Menu $menu)
    {
        $pivotTable = "{$day}_daily_meal";
        $discountTable = "{$day}_discounts";

        $pivotId = DB::table($pivotTable)
            ->where('week_schedule_id', $weekSchedule->id)
            ->where('menu_id', $menu->id)
            ->value('id');

        if (!$pivotId) {
            return response()->json(['error' => 'Menu not found in the specified day'], 404);
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
