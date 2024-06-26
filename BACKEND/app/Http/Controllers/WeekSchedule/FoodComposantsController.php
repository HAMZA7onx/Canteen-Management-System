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
}
