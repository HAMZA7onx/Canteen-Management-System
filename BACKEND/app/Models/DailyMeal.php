<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyMeal extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'daily_meal_menu');
    }

    public function mondayDailyMeals()
    {
        return $this->belongsToMany(WeekSchedule::class, 'monday_daily_meal', 'daily_meal_id', 'week_schedule_id');
    }

    public function tuesdayDailyMeals()
    {
        return $this->belongsToMany(WeekSchedule::class, 'tuesday_daily_meal', 'daily_meal_id', 'week_schedule_id');
    }

    public function wednesdayDailyMeals()
    {
        return $this->belongsToMany(WeekSchedule::class, 'wednesday_daily_meal', 'daily_meal_id', 'week_schedule_id');
    }

    public function thursdayDailyMeals()
    {
        return $this->belongsToMany(WeekSchedule::class, 'thursday_daily_meal', 'daily_meal_id', 'week_schedule_id');
    }

    public function fridayDailyMeals()
    {
        return $this->belongsToMany(WeekSchedule::class, 'friday_daily_meal', 'daily_meal_id', 'week_schedule_id');
    }

    public function saturdayDailyMeals()
    {
        return $this->belongsToMany(WeekSchedule::class, 'saturday_daily_meal', 'daily_meal_id', 'week_schedule_id');
    }

    public function sundayDailyMeals()
    {
        return $this->belongsToMany(WeekSchedule::class, 'sunday_daily_meal', 'daily_meal_id', 'week_schedule_id');
    }
}
