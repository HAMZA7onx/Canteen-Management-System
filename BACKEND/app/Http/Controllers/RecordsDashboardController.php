<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeekSchedule;
use App\Models\DailyMeal;
use App\Models\Badge;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RecordsDashboardController extends Controller
{
    private $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    public function getYears()
    {
        $years = [];
        foreach ($this->days as $day) {
            $yearsForDay = DB::table("{$day}_records")
                ->selectRaw('DISTINCT EXTRACT(YEAR FROM created_at) as year')
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->toArray();
            $years = array_merge($years, $yearsForDay);
        }
        return response()->json(array_unique($years));
    }

    public function getMonths($year)
    {
        $months = [];
        foreach ($this->days as $day) {
            $monthsForDay = DB::table("{$day}_records")
                ->selectRaw('DISTINCT EXTRACT(MONTH FROM created_at) as month')
                ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [$year])
                ->orderBy('month')
                ->pluck('month')
                ->toArray();
            $months = array_merge($months, $monthsForDay);
        }
        return response()->json(array_unique($months));
    }

    public function getDays($year, $month)
    {
        $days = [];
        foreach ($this->days as $day) {
            $daysForDay = DB::table("{$day}_records")
                ->selectRaw('DISTINCT EXTRACT(DAY FROM created_at) as day')
                ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [$year])
                ->whereRaw('EXTRACT(MONTH FROM created_at) = ?', [$month])
                ->orderBy('day')
                ->pluck('day')
                ->toArray();
            $days = array_merge($days, $daysForDay);
        }
        return response()->json(array_unique($days));
    }

    public function getDayRecords($year, $month, $day)
    {
        $records = [];
        foreach ($this->days as $dayOfWeek) {
            $dayRecords = DB::table("{$dayOfWeek}_records")
                ->join("{$dayOfWeek}_daily_meal", "{$dayOfWeek}_daily_meal.id", "=", "{$dayOfWeek}_records.{$dayOfWeek}_daily_meal_id")
                ->join('week_schedule', 'week_schedule.id', '=', "{$dayOfWeek}_daily_meal.week_schedule_id")
                ->join('daily_meals', 'daily_meals.id', '=', "{$dayOfWeek}_daily_meal.daily_meal_id")
                ->join('badges', 'badges.id', '=', "{$dayOfWeek}_records.badge_id")
                ->join('users', 'users.id', '=', 'badges.user_id')
                ->whereRaw('EXTRACT(YEAR FROM '.$dayOfWeek.'_records.created_at) = ?', [$year])
                ->whereRaw('EXTRACT(MONTH FROM '.$dayOfWeek.'_records.created_at) = ?', [$month])
                ->whereRaw('EXTRACT(DAY FROM '.$dayOfWeek.'_records.created_at) = ?', [$day])
                ->select(
                    'week_schedule.mode_name as week_schedule_name',
                    'daily_meals.name as meal_name',
                    "{$dayOfWeek}_daily_meal.start_time",
                    "{$dayOfWeek}_daily_meal.end_time",
                    "{$dayOfWeek}_daily_meal.price",
                    DB::raw('COUNT(DISTINCT badges.id) as badge_count'),
                    DB::raw('STRING_AGG(DISTINCT CONCAT(users.name, \' (\', badges.rfid, \')\'), \', \') as users')
                )
                ->groupBy(
                    'week_schedule.mode_name',
                    'daily_meals.name',
                    "{$dayOfWeek}_daily_meal.start_time",
                    "{$dayOfWeek}_daily_meal.end_time",
                    "{$dayOfWeek}_daily_meal.price"
                )
                ->get();
            $records = array_merge($records, $dayRecords->toArray());
        }

        return response()->json($records);
    }


}
