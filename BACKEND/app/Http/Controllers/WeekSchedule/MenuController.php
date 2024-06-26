<?php

namespace App\Http\Controllers\WeekSchedule;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\DailyMeal;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with([
            'foodComposants'
        ])->get();
        return response()->json($menus);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $menu = Menu::create($validatedData);
        return response()->json($menu, 201);
    }

    public function show(Menu $menu)
    {
        $menu->load([
            'foodComposants'
        ]);
        return response()->json($menu);
    }

    public function update(Request $request, Menu $menu)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $menu->update($validatedData);

        return response()->json($menu);
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->json(null, 204);
    }

    /**
     * Attach a menu to a daily meal.
     */
    public function attachToDailyMeal(Request $request, Menu $menu)
    {
        $validatedData = $request->validate([
            'daily_meal_id' => 'required|exists:daily_meals,id',
        ]);

        $dailyMeal = DailyMeal::findOrFail($validatedData['daily_meal_id']);
        $menu->daily_meals()->attach($dailyMeal);

        return response()->json(['message' => 'Menu attached to the daily meal']);
    }

    /**
     * Detach a menu from a daily meal.
     */
    public function detachFromDailyMeal(Menu $menu, DailyMeal $dailyMeal)
    {
        $menu->daily_meals()->detach($dailyMeal);

        return response()->json(['message' => 'Menu detached from the daily meal']);
    }
}
