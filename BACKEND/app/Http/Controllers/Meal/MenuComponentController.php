<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\MealMenu;
use App\Models\Component;
use Illuminate\Http\Request;

class MenuComponentController extends Controller
{
    /**
     * Display a listing of menu components.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menuComponents = MealMenu::with('components')->get();
        return response()->json($menuComponents);
    }

    /**
     * Store a new menu component record in the pivot table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'meal_menu_id' => 'required|exists:meal_menus,id',
            'component_id' => 'required|exists:components,id',
        ]);

        $mealMenu = MealMenu::findOrFail($validatedData['meal_menu_id']);
        $component = Component::findOrFail($validatedData['component_id']);

        $mealMenu->components()->attach($component->id);

        return response()->json(['message' => 'Component attached to meal menu successfully']);
    }

    /**
     * Update the menu components for a given meal menu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $mealMenuId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $mealMenuId)
    {
        $validatedData = $request->validate([
            'component_ids' => 'required|array',
            'component_ids.*' => 'exists:components,id',
        ]);

        $mealMenu = MealMenu::findOrFail($mealMenuId);
        $mealMenu->components()->sync($validatedData['component_ids']);

        return response()->json(['message' => 'Menu components updated successfully']);
    }

    /**
     * Remove a menu component record from the pivot table.
     *
     * @param  int  $mealMenuId
     * @param  int  $componentId
     * @return \Illuminate\Http\Response
     */
    public function destroy($mealMenuId, $componentId)
    {
        $mealMenu = MealMenu::findOrFail($mealMenuId);
        $component = Component::findOrFail($componentId);

        $mealMenu->components()->detach($component->id);

        return response()->json(['message' => 'Component detached from meal menu successfully']);
    }
}
