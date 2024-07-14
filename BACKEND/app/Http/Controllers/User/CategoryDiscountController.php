<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryDiscount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



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

    public function getDiscountsForMeal($day, $mealId)
    {
        $day = Str::lower($day);
        $validDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        if (!in_array($day, $validDays)) {
            return response()->json(['error' => 'Invalid day provided'], 400);
        }

        $mealTable = "{$day}_daily_meal";
        $discountTable = "{$day}_discounts";

        try {
            // First, check if the meal exists for the given day
            $mealExists = DB::table($mealTable)->where('id', $mealId)->exists();

            if (!$mealExists) {
                return response()->json(['message' => "No meal found with ID {$mealId} on " . ucfirst($day)], 404);
            }

            // If the meal exists, proceed to fetch discounts
            $discounts = DB::table($discountTable)
                ->join('user_category', 'user_category.id', '=', "{$discountTable}.category_id")
                ->where("{$discountTable}.meal_id", $mealId)
                ->select('user_category.name as category_name', "{$discountTable}.discount")
                ->get();

            if ($discounts->isEmpty()) {
                return response()->json(['message' => 'No discounts found for this meal on ' . ucfirst($day)], 404);
            }

            $formattedDiscounts = $discounts->map(function ($discount) {
                return "{$discount->category_name}: {$discount->discount}";
            });

            return response()->json($formattedDiscounts);

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching discounts: ' . $e->getMessage()], 500);
        }
    }
}
