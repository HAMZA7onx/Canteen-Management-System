<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Badge;
use App\Models\WeekSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CollaboratorStatisticsController extends Controller
{
    public function getStatistics(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $statistics = [];

        $users = User::all();

        foreach ($users as $user) {
            $userStats = $this->getUserStatistics($user, $startDate, $endDate);
            if (!empty($userStats)) {
                $statistics = array_merge_recursive($statistics, $userStats);
            }
        }

        // Sort the statistics by month
        ksort($statistics);

        return response()->json($statistics);
    }

    private function getUserStatistics($user, $startDate, $endDate)
    {
        $statistics = [];

        $badge = Badge::where('user_id', $user->id)->first();
        if (!$badge) {
            return [];
        }

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($days as $day) {
            $records = DB::table("{$day}_records")
                ->join("{$day}_daily_meal", "{$day}_daily_meal.id", "=", "{$day}_records.{$day}_daily_meal_id")
                ->join('week_schedule', 'week_schedule.id', '=', "{$day}_daily_meal.week_schedule_id")
                ->where("{$day}_records.badge_id", $badge->id)
                ->whereBetween('week_schedule.created_at', [$startDate, $endDate])
                ->get();

            foreach ($records as $record) {
                $month = Carbon::parse($record->created_at)->format('Y-m');

                if (!isset($statistics[$month])) {
                    $statistics[$month] = [];
                }

                if (!isset($statistics[$month][$user->id])) {
                    $statistics[$month][$user->id] = [
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'matriculation_number' => $user->matriculation_number,
                        'category' => $user->category->name,
                        'total_without_discount' => 0,
                        'total_with_discount' => 0,
                        'meals' => [],
                    ];
                }

                $discount = DB::table("{$day}_discounts")
                    ->where('meal_id', $record->{"{$day}_daily_meal_id"})
                    ->where('category_id', $user->category_id)
                    ->value('discount');

                $priceWithDiscount = $record->price * (1 - ($discount / 100));

                $statistics[$month][$user->id]['total_without_discount'] += $record->price;
                $statistics[$month][$user->id]['total_with_discount'] += $priceWithDiscount;
                $statistics[$month][$user->id]['meals'][] = [
                    'date' => $record->created_at,
                    'name' => $record->meal_name,
                    'price' => $record->price,
                    'discount' => $discount,
                    'price_with_discount' => $priceWithDiscount,
                ];
            }
        }

        return $statistics;
    }
}
