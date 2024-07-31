<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeekSchedule;
use App\Models\DailyMeal;
use App\Models\UserCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DailyMealStatsController extends Controller
{
    public function getDailyStats()
    {
        $currentDay = strtolower(Carbon::now()->format('l'));
        $pivotTable = "{$currentDay}_daily_meal";
        $recordTable = "{$currentDay}_records";
        $discountTable = "{$currentDay}_discounts";

        $activeSchedule = WeekSchedule::where('status', 'active')->first();

        if (!$activeSchedule) {
            return response()->json(['error' => 'No active schedule found'], 404);
        }

        $meals = DB::table($pivotTable)
            ->join('daily_meals', 'daily_meals.id', '=', "{$pivotTable}.daily_meal_id")
            ->where("{$pivotTable}.week_schedule_id", $activeSchedule->id)
            ->select('daily_meals.id', 'daily_meals.name', "{$pivotTable}.start_time", "{$pivotTable}.end_time", "{$pivotTable}.price", "{$pivotTable}.id as pivot_id")
            ->get();

        $mealStats = [];

        foreach ($meals as $meal) {
            $attendees = DB::table($recordTable)
                ->join('badges', 'badges.id', '=', "{$recordTable}.badge_id")
                ->join('users', 'users.id', '=', 'badges.user_id')
                ->join('user_category', 'user_category.id', '=', 'users.category_id')
                ->where("{$recordTable}.{$currentDay}_daily_meal_id", $meal->pivot_id)
                ->select('users.id', 'users.name', 'users.matriculation_number', 'user_category.name as category_name', 'user_category.id as category_id')
                ->get();



            $totalRevenue = $meal->price * count($attendees);
            $totalDiscounted = 0;

            foreach ($attendees as &$attendee) {
                $discount = DB::table($discountTable)
                    ->where('meal_id', $meal->pivot_id)
                    ->where('category_id', $attendee->category_id)
                    ->value('discount');

                $attendee->discount = $discount ?? 0;
                $attendee->discounted_price = $meal->price - ($meal->price * ($attendee->discount / 100));
                $totalDiscounted += $attendee->discounted_price;
            }

            $mealStats[] = [
                'id' => $meal->id,
                'name' => $meal->name,
                'start_time' => $meal->start_time,
                'end_time' => $meal->end_time,
                'price' => $meal->price,
                'attendee_count' => count($attendees),
                'total_revenue' => $totalRevenue,
                'total_discounted' => $totalDiscounted,
                'attendees' => $attendees,
            ];
        }

        return response()->json($mealStats);
    }
}
