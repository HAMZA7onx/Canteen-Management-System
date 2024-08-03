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
        $foodComposants = FoodComposant::orderBy('updated_at', 'desc')->get();
        return response()->json($foodComposants);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:food_composants,name',
            'description' => 'nullable',
        ]);

        try {
            $foodComposant = FoodComposant::create($validatedData);
            return response()->json($foodComposant, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['error' => 'A food composant with this name already exists.'], 422);
            }
            throw $e;
        }
    }

    public function update(Request $request, FoodComposant $foodComposant)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:food_composants,name,' . $foodComposant->id,
            'description' => 'nullable',
        ]);

        try {
            $foodComposant->update($validatedData);
            return response()->json($foodComposant);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['error' => 'A food composant with this name already exists.'], 422);
            }
            throw $e;
        }
    }

    public function show(FoodComposant $foodComposant)
    {
        return response()->json($foodComposant);
    }

    public function destroy(FoodComposant $foodComposant)
    {
        $foodComposant->delete();

        return response()->json(null, 204);
    }
}
