<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeekSchedule;
use App\Models\DailyMeal;
use App\Models\Badge;
use Carbon\CarbonPeriod;
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
        $monthlyTotals = [];

        foreach ($this->days as $dayOfWeek) {
            $dayRecords = DB::table("{$dayOfWeek}_records")
                ->join("{$dayOfWeek}_daily_meal", "{$dayOfWeek}_daily_meal.id", "=", "{$dayOfWeek}_records.{$dayOfWeek}_daily_meal_id")
                ->join('week_schedule', 'week_schedule.id', '=', "{$dayOfWeek}_daily_meal.week_schedule_id")
                ->join('daily_meals', 'daily_meals.id', '=', "{$dayOfWeek}_daily_meal.daily_meal_id")
                ->join('badges', 'badges.id', '=', "{$dayOfWeek}_records.badge_id")
                ->join('users', 'users.id', '=', 'badges.user_id')
                ->join('user_category', 'user_category.id', '=', 'users.category_id')
                ->leftJoin("{$dayOfWeek}_discounts", function($join) use ($dayOfWeek) {
                    $join->on("{$dayOfWeek}_discounts.meal_id", "=", "{$dayOfWeek}_daily_meal.id")
                        ->on("{$dayOfWeek}_discounts.category_id", "=", "user_category.id");
                })
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
                    DB::raw('JSON_AGG(
                    JSON_BUILD_OBJECT(
                        \'email\', users.email,
                        \'badge_id\', badges.rfid,
                        \'category_discount\', COALESCE('.$dayOfWeek.'_discounts.discount, 0),
                        \'user_category_name\', user_category.name
                    )
                ) as users')
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

            // Calculate monthly totals
            $dayTotals = DB::table("{$dayOfWeek}_records")
                ->join("{$dayOfWeek}_daily_meal", "{$dayOfWeek}_daily_meal.id", "=", "{$dayOfWeek}_records.{$dayOfWeek}_daily_meal_id")
                ->join('badges', 'badges.id', '=', "{$dayOfWeek}_records.badge_id")
                ->join('users', 'users.id', '=', 'badges.user_id')
                ->join('user_category', 'user_category.id', '=', 'users.category_id')
                ->leftJoin("{$dayOfWeek}_discounts", function($join) use ($dayOfWeek) {
                    $join->on("{$dayOfWeek}_discounts.meal_id", "=", "{$dayOfWeek}_daily_meal.id")
                        ->on("{$dayOfWeek}_discounts.category_id", "=", "user_category.id");
                })
                ->whereRaw('EXTRACT(YEAR FROM '.$dayOfWeek.'_records.created_at) = ?', [$year])
                ->whereRaw('EXTRACT(MONTH FROM '.$dayOfWeek.'_records.created_at) = ?', [$month])
                ->groupBy('users.id', 'users.email')
                ->select(
                    'users.id',
                    'users.email',
                    DB::raw('SUM('.$dayOfWeek.'_daily_meal.price) as total_without_discount'),
                    DB::raw('SUM('.$dayOfWeek.'_daily_meal.price - COALESCE('.$dayOfWeek.'_discounts.discount, 0)) as total_with_discount')
                )
                ->get();

            foreach ($dayTotals as $total) {
                $userId = $total->id;
                if (!isset($monthlyTotals[$userId])) {
                    $monthlyTotals[$userId] = [
                        'id' => $userId,
                        'email' => $total->email,
                        'total_without_discount' => 0,
                        'total_with_discount' => 0
                    ];
                }
                $monthlyTotals[$userId]['total_without_discount'] += $total->total_without_discount;
                $monthlyTotals[$userId]['total_with_discount'] += $total->total_with_discount;
            }
        }

        // Convert associative array to indexed array
        $monthlyTotals = array_values($monthlyTotals);

        return response()->json([
            'records' => $records,
            'monthlyTotals' => $monthlyTotals
        ]);
    }


    public function getMonthlyTotals($year, $month)
    {
        $monthlyTotals = [];

        foreach ($this->days as $dayOfWeek) {
            $dayTotals = DB::table("{$dayOfWeek}_records")
                ->join("{$dayOfWeek}_daily_meal", "{$dayOfWeek}_daily_meal.id", "=", "{$dayOfWeek}_records.{$dayOfWeek}_daily_meal_id")
                ->join('badges', 'badges.id', '=', "{$dayOfWeek}_records.badge_id")
                ->join('users', 'users.id', '=', 'badges.user_id')
                ->join('user_category', 'user_category.id', '=', 'users.category_id')
                ->leftJoin("{$dayOfWeek}_discounts", function($join) use ($dayOfWeek) {
                    $join->on("{$dayOfWeek}_discounts.meal_id", "=", "{$dayOfWeek}_daily_meal.id")
                        ->on("{$dayOfWeek}_discounts.category_id", "=", "user_category.id");
                })
                ->whereRaw('EXTRACT(YEAR FROM '.$dayOfWeek.'_records.created_at) = ?', [$year])
                ->whereRaw('EXTRACT(MONTH FROM '.$dayOfWeek.'_records.created_at) = ?', [$month])
                ->groupBy('users.id', 'users.email')
                ->select(
                    'users.id',
                    'users.email',
                    DB::raw('SUM('.$dayOfWeek.'_daily_meal.price) as total_without_discount'),
                    DB::raw('SUM('.$dayOfWeek.'_daily_meal.price - COALESCE('.$dayOfWeek.'_discounts.discount, 0)) as total_with_discount')
                )
                ->get();

            foreach ($dayTotals as $total) {
                $userId = $total->id;
                if (!isset($monthlyTotals[$userId])) {
                    $monthlyTotals[$userId] = [
                        'id' => $userId,
                        'email' => $total->email,
                        'total_without_discount' => 0,
                        'total_with_discount' => 0
                    ];
                }
                $monthlyTotals[$userId]['total_without_discount'] += $total->total_without_discount;
                $monthlyTotals[$userId]['total_with_discount'] += $total->total_with_discount;
            }
        }

        // Convert associative array to indexed array
        $monthlyTotals = array_values($monthlyTotals);

        return response()->json([
            'monthlyTotals' => $monthlyTotals
        ]);
    }

//    public function getAdvancedRecords(Request $request)
//    {
//        $request->validate([
//            'start_date' => 'required|date',
//            'end_date' => 'required|date|after_or_equal:start_date',
//            'user_category' => 'nullable|exists:user_category,id',
//            'search' => 'nullable|string',
//        ]);
//
//        $startDate = Carbon::parse($request->start_date)->startOfDay();
//        $endDate = Carbon::parse($request->end_date)->endOfDay();
//        $userCategory = $request->user_category;
//        $search = $request->search;
//        $records = [];
//
//        $daysWithRecords = DB::table('week_schedule')
//            ->whereBetween('created_at', [$startDate, $endDate])
//            ->pluck('created_at')
//            ->map(function ($date) {
//                return Carbon::parse($date)->format('Y-m-d');
//            })
//            ->unique();
//
//        \Log::info('Days with records: ' . json_encode($daysWithRecords));
//
//        foreach ($daysWithRecords as $date) {
//            \Log::info("Processing date: $date");
//            $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
//            $pivotTable = "{$dayOfWeek}_daily_meal";
//            $recordTable = "{$dayOfWeek}_records";
//            $discountTable = "{$dayOfWeek}_discounts";
//
//            $dayRecords = DB::table($pivotTable)
//                ->join('daily_meals', 'daily_meals.id', '=', "{$pivotTable}.daily_meal_id")
//                ->join('week_schedule', 'week_schedule.id', '=', "{$pivotTable}.week_schedule_id")
//                ->leftJoin($recordTable, "{$recordTable}.{$dayOfWeek}_daily_meal_id", '=', "{$pivotTable}.id")
//                ->leftJoin('badges', 'badges.id', '=', "{$recordTable}.badge_id")
//                ->leftJoin('users', 'users.id', '=', 'badges.user_id')
//                ->leftJoin('user_category', 'user_category.id', '=', 'users.category_id')
//                ->leftJoin($discountTable, function ($join) use ($pivotTable, $discountTable) {
//                    $join->on("{$discountTable}.meal_id", '=', "{$pivotTable}.id")
//                        ->on("{$discountTable}.category_id", '=', 'user_category.id');
//                })
//                ->whereDate('week_schedule.created_at', $date)
//                ->select(
//                    'daily_meals.name as meal_name',
//                    "{$pivotTable}.start_time",
//                    "{$pivotTable}.end_time",
//                    "{$pivotTable}.price",
//                    DB::raw("COUNT(DISTINCT {$recordTable}.id) as persons_count"),
//                    DB::raw("SUM({$pivotTable}.price) as total_no_discount"),
//                    DB::raw("SUM({$pivotTable}.price * (1 - COALESCE({$discountTable}.discount, 0) / 100)) as total_with_discount")
//                )
//                ->groupBy('daily_meals.id', "{$pivotTable}.id", "{$pivotTable}.start_time", "{$pivotTable}.end_time", "{$pivotTable}.price");
//
//            if ($userCategory) {
//                $dayRecords->where('user_category.id', $userCategory);
//            }
//
//            if ($search) {
//                $dayRecords->where(function ($query) use ($search) {
//                    $query->where('users.name', 'like', "%{$search}%")
//                        ->orWhere('users.matriculation_number', 'like', "%{$search}%");
//                });
//            }
//
//            \Log::info("Query for $date: " . $dayRecords->toSql());
//            \Log::info("Bindings: " . json_encode($dayRecords->getBindings()));
//
//            $dayRecords = $dayRecords->get();
//
//            \Log::info("Number of records for $date: " . $dayRecords->count());
//
//            if ($dayRecords->isNotEmpty()) {
//                $records[] = [
//                    'date' => $date,
//                    'meals' => $dayRecords
//                ];
//            }
//        }
//
//        \Log::info('Returning records: ' . json_encode($records));
//        return response()->json($records);
//    }


    public function getAdvancedRecords(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'user_category' => 'nullable|exists:user_category,id',
            'search' => 'nullable|string',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $userCategory = $request->user_category;
        $search = $request->search;

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $records = [];
        $monthlyTotals = [];

        foreach ($days as $day) {
            $dayRecords = DB::table("{$day}_records")
                ->join("{$day}_daily_meal", "{$day}_daily_meal.id", "=", "{$day}_records.{$day}_daily_meal_id")
                ->join('week_schedule', 'week_schedule.id', '=', "{$day}_daily_meal.week_schedule_id")
                ->join('daily_meals', 'daily_meals.id', '=', "{$day}_daily_meal.daily_meal_id")
                ->join('badges', 'badges.id', '=', "{$day}_records.badge_id")
                ->join('users', 'users.id', '=', 'badges.user_id')
                ->join('user_category', 'user_category.id', '=', 'users.category_id')
                ->leftJoin("{$day}_discounts", function($join) use ($day) {
                    $join->on("{$day}_discounts.meal_id", "=", "{$day}_daily_meal.id")
                        ->on("{$day}_discounts.category_id", "=", "user_category.id");
                })
                ->whereBetween("{$day}_records.created_at", [$startDate, $endDate])
                ->when($userCategory, function ($query) use ($userCategory) {
                    return $query->where('user_category.id', $userCategory);
                })
                ->when($search, function ($query) use ($search) {
                    return $query->where(function ($q) use ($search) {
                        $q->where('users.name', 'like', "%{$search}%")
                            ->orWhere('users.matriculation_number', 'like', "%{$search}%");
                    });
                })
                ->select(
                    DB::raw("DATE({$day}_records.created_at) as date"),
                    'week_schedule.mode_name as week_schedule_name',
                    'daily_meals.name as meal_name',
                    "{$day}_daily_meal.start_time",
                    "{$day}_daily_meal.end_time",
                    "{$day}_daily_meal.price",
                    DB::raw('COUNT(DISTINCT badges.id) as badge_count'),
                    DB::raw('JSON_AGG(
                    JSON_BUILD_OBJECT(
                        \'email\', users.email,
                        \'badge_id\', badges.rfid,
                        \'category_discount\', COALESCE('.$day.'_discounts.discount, 0),
                        \'user_category_name\', user_category.name
                    )
                ) as users'),
                    DB::raw('SUM('.$day.'_daily_meal.price) as total_without_discount'),
                    DB::raw('SUM('.$day.'_daily_meal.price * (1 - COALESCE('.$day.'_discounts.discount, 0) / 100)) as total_with_discount')
                )
                ->groupBy(
                    DB::raw("DATE({$day}_records.created_at)"),
                    'week_schedule.mode_name',
                    'daily_meals.name',
                    "{$day}_daily_meal.start_time",
                    "{$day}_daily_meal.end_time",
                    "{$day}_daily_meal.price"
                )
                ->get();

            foreach ($dayRecords as $record) {
                $date = $record->date;
                if (!isset($records[$date])) {
                    $records[$date] = [];
                }
                $records[$date][] = $record;

                // Calculate monthly totals
                $users = json_decode($record->users);
                foreach ($users as $user) {
                    $userId = $user->email; // Using email as unique identifier
                    if (!isset($monthlyTotals[$userId])) {
                        $monthlyTotals[$userId] = [
                            'email' => $user->email,
                            'total_without_discount' => 0,
                            'total_with_discount' => 0
                        ];
                    }
                    $monthlyTotals[$userId]['total_without_discount'] += $record->price;
                    $monthlyTotals[$userId]['total_with_discount'] += $record->price * (1 - $user->category_discount / 100);
                }
            }
        }

        // Sort records by date
        ksort($records);

        // Convert records to the desired format
        $formattedRecords = [];
        foreach ($records as $date => $dayRecords) {
            $formattedRecords[] = [
                'date' => $date,
                'meals' => $dayRecords
            ];
        }

        // Convert monthly totals to indexed array
        $monthlyTotals = array_values($monthlyTotals);

        \Log::info('API Response', [
            'records' => $formattedRecords,
            'monthlyTotals' => $monthlyTotals
        ]);

        return response()->json([
            'records' => $formattedRecords,
            'monthlyTotals' => $monthlyTotals
        ]);
    }

}
