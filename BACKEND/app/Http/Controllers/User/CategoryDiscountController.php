<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryDiscount;

class CategoryDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categoryDiscounts = CategoryDiscount::all();
        return response()->json($categoryDiscounts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:user_category,id',
            'meal_schedule_id' => 'required|exists:meal_schedules,id',
            'meal_discount' => 'required|numeric|min:0',
        ]);

        $categoryDiscount = CategoryDiscount::create($validatedData);
        return response()->json($categoryDiscount, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $categoryDiscount = CategoryDiscount::findOrFail($id);
        return response()->json($categoryDiscount);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:user_category,id',
            'meal_schedule_id' => 'required|exists:meal_schedules,id',
            'meal_discount' => 'required|numeric|min:0',
        ]);

        $categoryDiscount = CategoryDiscount::findOrFail($id);
        $categoryDiscount->update($validatedData);
        return response()->json($categoryDiscount);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoryDiscount = CategoryDiscount::findOrFail($id);
        $categoryDiscount->delete();
        return response()->json(['message' => 'Category discount deleted successfully']);
    }
}
