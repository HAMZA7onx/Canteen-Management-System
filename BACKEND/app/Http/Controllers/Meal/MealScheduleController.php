<?php
namespace App\Http\Controllers\Meal;
use App\Http\Controllers\Controller;
use App\Models\MealSchedule;
use Illuminate\Http\Request;

class MealScheduleController extends Controller
{
    public function index()
    {
        $mealSchedules = MealSchedule::with('mealName', 'mealMenus')->get();
        return response()->json($mealSchedules);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'meal_name_id' => 'required|exists:meal_names,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'meal_menu_ids' => 'required|array',
            'meal_menu_ids.*' => 'exists:meal_menus,id',
        ]);

        $mealSchedule = MealSchedule::create([
            'meal_name_id' => $validatedData['meal_name_id'],
            'date' => $validatedData['date'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
        ]);

        $mealSchedule->mealMenus()->attach($validatedData['meal_menu_ids']);

        return response()->json($mealSchedule->load('mealName', 'mealMenus'), 201);
    }

    public function show($id)
    {
        $mealSchedule = MealSchedule::with('mealName', 'mealMenus')->findOrFail($id);
        return response()->json($mealSchedule);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'meal_name_id' => 'required|exists:meal_names,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'meal_menu_ids' => 'required|array',
            'meal_menu_ids.*' => 'exists:meal_menus,id',
        ]);

        $mealSchedule = MealSchedule::findOrFail($id);
        $mealSchedule->update([
            'meal_name_id' => $validatedData['meal_name_id'],
            'date' => $validatedData['date'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
        ]);

        $mealSchedule->mealMenus()->sync($validatedData['meal_menu_ids']);

        return response()->json($mealSchedule->load('mealName', 'mealMenus'));
    }

    public function destroy($id)
    {
        $mealSchedule = MealSchedule::findOrFail($id);
        $mealSchedule->mealMenus()->detach();
        $mealSchedule->delete();

        return response()->json(null, 204);
    }
}
