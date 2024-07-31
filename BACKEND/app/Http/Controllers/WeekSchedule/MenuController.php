<?php

namespace App\Http\Controllers\WeekSchedule;

use App\Http\Controllers\Controller;
use App\Models\FoodComposant;
use App\Models\Menu;
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

    public function attachFoodComposants(Menu $menu, Request $request)
    {
        $request->validate([
            'foodComposantIds' => 'required|array',
            'foodComposantIds.*' => 'exists:food_composants,id'
        ]);

        $foodComposantIds = $request->input('foodComposantIds');
        $menu->foodComposants()->attach($foodComposantIds);

        return response()->json(['message' => 'Food composants attached to the menu']);
    }


    public function detachFoodComposant(Menu $menu, FoodComposant $foodComposant)
    {
        $menu->foodComposants()->detach($foodComposant);
        return response()->json(['message' => 'Food composant detached from the menu']);
    }
}
