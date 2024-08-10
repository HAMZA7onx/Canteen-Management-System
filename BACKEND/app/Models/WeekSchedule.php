<?php

namespace App\Models;

use App\Models\PivotDays\FridayDailyMeal;
use App\Models\PivotDays\MondayDailyMeal;
use App\Models\PivotDays\SaturdayDailyMeal;
use App\Models\PivotDays\SundayDailyMeal;
use App\Models\PivotDays\ThursdayDailyMeal;
use App\Models\PivotDays\TuesdayDailyMeal;
use App\Models\PivotDays\WednesdayDailyMeal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeekSchedule extends Model
{
    use HasFactory;

    protected $table = 'week_schedule';

    protected $casts = [
        'editors' => 'json',
    ];

    protected $fillable = ['mode_name', 'description', 'creator', 'editors', 'status'];

    public function mondayMenus()
    {
        return $this->belongsToMany(Menu::class, MondayDailyMeal::class)
            ->withPivot('meal_name', 'start_time', 'end_time', 'price');
    }

    public function tuesdayMenus()
    {
        return $this->belongsToMany(Menu::class, TuesdayDailyMeal::class)
            ->withPivot('meal_name', 'start_time', 'end_time', 'price');
    }

    public function wednesdayMenus()
    {
        return $this->belongsToMany(Menu::class, WednesdayDailyMeal::class)
            ->withPivot('meal_name', 'start_time', 'end_time', 'price');
    }

    public function thursdayMenus()
    {
        return $this->belongsToMany(Menu::class, ThursdayDailyMeal::class)
            ->withPivot('meal_name', 'start_time', 'end_time', 'price');
    }

    public function fridayMenus()
    {
        return $this->belongsToMany(Menu::class, FridayDailyMeal::class)
            ->withPivot('meal_name', 'start_time', 'end_time', 'price');
    }

    public function saturdayMenus()
    {
        return $this->belongsToMany(Menu::class, SaturdayDailyMeal::class)
            ->withPivot('meal_name', 'start_time', 'end_time', 'price');
    }

    public function sundayMenus()
    {
        return $this->belongsToMany(Menu::class, SundayDailyMeal::class)
            ->withPivot('meal_name', 'start_time', 'end_time', 'price');
    }
}
