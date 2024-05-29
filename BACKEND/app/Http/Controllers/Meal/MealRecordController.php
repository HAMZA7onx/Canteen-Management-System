<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\MealRecord;
use Illuminate\Http\Request;

class MealRecordController extends Controller
{
    public function index()
    {
        $mealRecords = MealRecord::all();
        return response()->json($mealRecords);
    }

    public function store(Request $request)
    {
        $mealRecord = MealRecord::create($request->all());
        return response()->json($mealRecord, 201);
    }

    public function show($id)
    {
        $mealRecord = MealRecord::findOrFail($id);
        return response()->json($mealRecord);
    }

    public function update(Request $request, $id)
    {
        $mealRecord = MealRecord::findOrFail($id);
        $mealRecord->update($request->all());
        return response()->json($mealRecord);
    }

    public function destroy($id)
    {
        $mealRecord = MealRecord::findOrFail($id);
        $mealRecord->delete();
        return response()->json(null, 204);
    }
}
