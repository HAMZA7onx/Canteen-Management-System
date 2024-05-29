<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\MealItem;
use Illuminate\Http\Request;

class MealItemController extends Controller
{
    public function index()
    {
        $mealItems = MealItem::all();
        return response()->json($mealItems);
    }

    public function store(Request $request)
    {
        $mealItem = MealItem::create($request->all());
        return response()->json($mealItem, 201);
    }

    public function show($id)
    {
        $mealItem = MealItem::findOrFail($id);
        return response()->json($mealItem);
    }

    public function update(Request $request, $id)
    {
        $mealItem = MealItem::findOrFail($id);
        $mealItem->update($request->all());
        return response()->json($mealItem);
    }

    public function destroy($id)
    {
        $mealItem = MealItem::findOrFail($id);
        $mealItem->delete();
        return response()->json(null, 204);
    }
}
