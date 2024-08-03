<?php

namespace App\Http\Controllers\WeekSchedule;

use App\Http\Controllers\Controller;
use App\Models\FoodComposant;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with([
            'foodComposants'
        ])
            ->orderBy('updated_at', 'desc')
            ->get();
        return response()->json($menus);
    }

    public function store(Request $request)
    {
        \Log::info('Create request:', ['all' => $request->all(), 'files' => $request->allFiles()]);
        $validatedData = $request->validate([
            'name' => 'required|unique:menu,name',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,webp,png,jpg,gif|max:2048',
        ]);

        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('menu_images', 'public');
                $validatedData['image'] = $imagePath;
            }

            $menu = Menu::create($validatedData);
            return response()->json($menu, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['error' => 'A menu with this name already exists.'], 422);
            }
            throw $e;
        }
    }

    public function update(Request $request, Menu $menu)
    {
        \Log::info('Update request:', ['all' => $request->all(), 'files' => $request->allFiles()]);
        $validatedData = $request->validate([
            'name' => 'required|unique:menu,name,' . $menu->id,
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,webp,png,jpg,gif|max:2048',
        ]);

        try {
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($menu->image) {
                    Storage::disk('public')->delete($menu->image);
                }

                $imagePath = $request->file('image')->store('menu_images', 'public');
                $validatedData['image'] = $imagePath;
            }

            $menu->update($validatedData);
            return response()->json($menu);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['error' => 'A menu with this name already exists.'], 422);
            }
            throw $e;
        }
    }

    public function show(Menu $menu)
    {
        $menu->load([
            'foodComposants'
        ]);
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
