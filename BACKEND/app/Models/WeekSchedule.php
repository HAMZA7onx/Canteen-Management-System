<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeekSchedule extends Model
{
    use HasFactory;
    protected $table = 'week_schedule';
    protected $fillable = ['mode_name', 'description', 'editor'];

    public function mondayMeals()
    {
        return $this->belongsToMany(DailyMeal::class)->using(PivotDays\MondayDailyMeal::class);
    }

    public function tuesdayMeals()
    {
        return $this->belongsToMany(DailyMeal::class)->using(PivotDays\TuesdayDailyMeal::class);
    }

    public function wednesdayMeals()
    {
        return $this->belongsToMany(DailyMeal::class)->using(PivotDays\WednesdayDailyMeal::class);
    }

    public function thursdayMeals()
    {
        return $this->belongsToMany(DailyMeal::class)->using(PivotDays\ThursdayDailyMeal::class);
    }

    public function fridayMeals()
    {
        return $this->belongsToMany(DailyMeal::class)->using(PivotDays\FridayDailyMeal::class);
    }

    public function saturdayMeals()
    {
        return $this->belongsToMany(DailyMeal::class)->using(PivotDays\SaturdayDailyMeal::class);
    }

    public function sundayMeals()
    {
        return $this->belongsToMany(DailyMeal::class)->using(PivotDays\SundayDailyMeal::class);
    }
}
