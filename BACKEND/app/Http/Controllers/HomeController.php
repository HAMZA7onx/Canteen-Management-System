<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Menu;
use App\Models\User;
use App\Models\WeekSchedule;

class HomeController extends Controller
{
    public function get_users()
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
            'mondayMenus.foodComposants',
            'tuesdayMenus.foodComposants',
            'wednesdayMenus.foodComposants',
            'thursdayMenus.foodComposants',
            'fridayMenus.foodComposants',
            'saturdayMenus.foodComposants',
            'sundayMenus.foodComposants',
        ])->get();

        // Add meal_name to each menu in the week schedule
        $weekSchedules->each(function ($weekSchedule) {
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($days as $day) {
                $relationName = $day . 'Menus';
                $weekSchedule->$relationName->each(function ($menu) use ($day) {
                    $menu->meal_name = $menu->pivot->meal_name;
                });
            }
        });

        return response()->json($weekSchedules);
    }
}
