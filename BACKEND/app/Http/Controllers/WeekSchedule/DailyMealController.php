<?php

namespace App\Http\Controllers\WeekSchedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyMeal;
use App\Models\Menu;

class DailyMealController extends Controller
{
    public function index()
    {
        $dailyMeals = DailyMeal::with([
            "menus.foodComposants"
        ])->get();
        return response()->json($dailyMeals);
    }

    public function show(DailyMeal $dailyMeal)
    {
        $dailyMeal = DailyMeal::where('id', $dailyMeal->id)->with([
            "menus.foodComposants"
        ])->get();
        return response()->json($dailyMeal);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);
        $dailyMeal = DailyMeal::create($validatedData);
        return response()->json($dailyMeal, 201);
    }

    public function update(Request $request, DailyMeal $dailyMeal)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);
        $dailyMeal->update($validatedData);
        return response()->json($dailyMeal);
    }

    public function destroy(DailyMeal $dailyMeal)
    {
        $dailyMeal->delete();
        return response()->json(null, 204);
    }

    public function attachMenu(Request $request, DailyMeal $dailyMeal, $menuId)
    {
        $menu = Menu::findOrFail($menuId);

        if (!$dailyMeal->menus()->where('menu.id', $menuId)->exists()) {
            $dailyMeal->menus()->attach($menuId);
            return response()->json([
                'message' => 'Menu attached to the daily meal successfully',
                'daily_meal' => $dailyMeal->load('menus')
            ], 200);
        }

        return response()->json([
            'message' => 'Menu is already attached to this daily meal'
        ], 422);
    }

    public function detachMenu(DailyMeal $dailyMeal, Menu $menu)
    {
        if ($dailyMeal->menus()->where('menu.id', $menu->id)->exists()) {
            $dailyMeal->menus()->detach($menu->id);
            return response()->json([
                'message' => 'Menu detached from the daily meal successfully',
                'daily_meal' => $dailyMeal->load('menus')
            ], 200);
        }

        return response()->json([
            'message' => 'Menu is not attached to this daily meal'
        ], 422);
    }
}
