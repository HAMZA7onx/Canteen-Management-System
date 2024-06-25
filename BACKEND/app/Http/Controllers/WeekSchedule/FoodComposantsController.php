<?php

namespace App\Http\Controllers\WeekSchedule;

use App\Http\Controllers\Controller;
use App\Models\FoodComposant;
use App\Models\Menu;
use Illuminate\Http\Request;

class FoodComposantsController extends Controller
{
    public function index()
    {
        $foodComposants = FoodComposant::latest()->get();
        return response()->json($foodComposants);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $foodComposant = FoodComposant::create($validatedData);

        return response()->json($foodComposant, 201);
    }

    public function show(FoodComposant $foodComposant)
    {
        return response()->json($foodComposant);
    }

    public function update(Request $request, FoodComposant $foodComposant)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $foodComposant->update($validatedData);

        return response()->json($foodComposant);
    }

    public function destroy(FoodComposant $foodComposant)
    {
        $foodComposant->delete();

        return response()->json(null, 204);
    }

    /**
     * Attach a food composant to a menu.
     */
    public function attachToMenu(Request $request, FoodComposant $foodComposant)
    {
        $validatedData = $request->validate([
            'menu_id' => 'required|exists:menu,id',
        ]);

        $menu = Menu::findOrFail($validatedData['menu_id']);
        $foodComposant->menus()->attach($menu);

        return response()->json(['message' => 'Food composant attached to the menu']);
    }

    /**
     * Dettach a food composant to a menu.
     */
    public function detachFromMenu(FoodComposant $foodComposant, Menu $menu)
    {
        $foodComposant->menus()->detach($menu);

        return response()->json(['message' => 'Food composant detached from the menu']);
    }
}
