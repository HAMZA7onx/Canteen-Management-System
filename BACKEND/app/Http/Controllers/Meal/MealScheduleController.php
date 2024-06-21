<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\MealSchedule;
use App\Models\CategoryDiscount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MealScheduleController extends Controller
{
    public function index()
    {
        $mealSchedules = MealSchedule::with('mealName', 'mealMenus', 'categoryDiscounts')->get();
        return response()->json($mealSchedules);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'meal_name_id' => 'required|exists:meal_names,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'meal_menu_ids' => 'required|array',
            'meal_menu_ids.*' => 'exists:meal_menus,id',
            'categoryDiscounts' => 'nullable|array',
            'categoryDiscounts.*.category_id' => 'required|exists:user_category,id',
            'categoryDiscounts.*.meal_discount' => 'required|numeric|between:0,100',
        ]);

        $mealSchedule = MealSchedule::create([
            'meal_name_id' => $validatedData['meal_name_id'],
            'date' => $validatedData['date'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
        ]);

        $mealSchedule->mealMenus()->attach($validatedData['meal_menu_ids']);

        if (isset($validatedData['categoryDiscounts'])) {
            foreach ($validatedData['categoryDiscounts'] as $categoryDiscount) {
                $mealSchedule->categoryDiscounts()->create([
                    'category_id' => $categoryDiscount['category_id'],
                    'meal_discount' => $categoryDiscount['meal_discount'],
                ]);
            }
        }

        return response()->json($mealSchedule->load('mealName', 'mealMenus', 'categoryDiscounts'), 201);
    }

    public function show($id)
    {
        $mealSchedule = MealSchedule::with('mealName', 'mealMenus', 'categoryDiscounts')->findOrFail($id);
        return response()->json($mealSchedule);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'meal_name_id' => 'required|exists:meal_names,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'meal_menu_ids' => 'required|array',
            'meal_menu_ids.*' => 'exists:meal_menus,id',
            'categoryDiscounts' => 'nullable|array',
            'categoryDiscounts.*.category_id' => 'required|exists:user_category,id',
            'categoryDiscounts.*.meal_discount' => 'required|numeric|between:0,100',
        ]);

        $mealSchedule = MealSchedule::findOrFail($id);

        $mealSchedule->update([
            'meal_name_id' => $validatedData['meal_name_id'],
            'date' => $validatedData['date'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
        ]);

        $mealSchedule->mealMenus()->sync($validatedData['meal_menu_ids']);

        // Delete existing category discounts
        $mealSchedule->categoryDiscounts()->delete();

        // Create new category discounts
        if (isset($validatedData['categoryDiscounts'])) {
            foreach ($validatedData['categoryDiscounts'] as $categoryDiscount) {
                $mealSchedule->categoryDiscounts()->create([
                    'category_id' => $categoryDiscount['category_id'],
                    'meal_discount' => $categoryDiscount['meal_discount'],
                ]);
            }
        }

        return response()->json($mealSchedule->load('mealName', 'mealMenus', 'categoryDiscounts'));
    }

    public function destroy($id)
    {
        $mealSchedule = MealSchedule::findOrFail($id);
        $mealSchedule->mealMenus()->detach();
        $mealSchedule->categoryDiscounts()->delete();
        $mealSchedule->delete();
        return response()->json(null, 204);
    }

    public function getCategoryDiscounts($id)
    {
        $mealSchedule = MealSchedule::findOrFail($id);
        $categoryDiscounts = $mealSchedule->categoryDiscounts;
        return response()->json($categoryDiscounts);
    }

    public function updateCategoryDiscounts(Request $request, $id)
    {
        Log::debug('Received request to update category discounts for meal schedule', ['id' => $id, 'request' => $request->all()]);

        $validatedData = $request->validate([
            'discounts' => 'required|array',
            'discounts.*.category_id' => 'required|exists:user_category,id',
            'discounts.*.meal_discount' => 'required|numeric|between:0,100',
        ]);

        $mealSchedule = MealSchedule::findOrFail($id);

        // Delete existing category discounts
        $mealSchedule->categoryDiscounts()->delete();

        // Create new category discounts
        foreach ($validatedData['discounts'] as $categoryDiscount) {
            $mealSchedule->categoryDiscounts()->create([
                'category_id' => $categoryDiscount['category_id'],
                'meal_discount' => $categoryDiscount['meal_discount'],
            ]);
        }

        return response()->json($mealSchedule->categoryDiscounts);
    }

}
