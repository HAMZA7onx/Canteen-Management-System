<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Menu;
use App\Models\User;
use App\Models\WeekSchedule;

class HomeController extends Controller
{
    function get_users()
    {
        $users = User::with('category')->orderBy('updated_at', 'desc')->get();
        return response()->json($users);
    }

    public function get_badges()
    {
        $badges = Badge::with('user')->orderBy('updated_at', 'desc')->get();
        return response()->json($badges);
    }

    public function get_menus()
    {
        $menus = Menu::with([
            'foodComposants'
        ])->get();
        return response()->json($menus);
    }

    public function get_week_schedules()
    {
        $weekSchedules = WeekSchedule::with([
            'mondayDailyMeals.menus.foodComposants',
            'tuesdayDailyMeals.menus.foodComposants',
            'wednesdayDailyMeals.menus.foodComposants',
            'thursdayDailyMeals.menus.foodComposants',
            'fridayDailyMeals.menus.foodComposants',
            'saturdayDailyMeals.menus.foodComposants',
            'sundayDailyMeals.menus.foodComposants',
        ])->get();

        return response()->json($weekSchedules);
    }
}
