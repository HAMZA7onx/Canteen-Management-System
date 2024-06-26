<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMeal extends Model
{
    use HasFactory;
    protected $table = 'daily_meals';
    protected $fillable = ['name', 'description'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function mondaySchedules()
    {
        return $this->belongsToMany(WeekSchedule::class)->using(PivotDays\MondayDailyMeal::class);
    }

    public function tuesdaySchedules()
    {
        return $this->belongsToMany(WeekSchedule::class)->using(PivotDays\TuesdayDailyMeal::class);
    }

    public function wednesdaySchedules()
    {
        return $this->belongsToMany(WeekSchedule::class)->using(PivotDays\WednesdayDailyMeal::class);
    }

    public function thursdaySchedules()
    {
        return $this->belongsToMany(WeekSchedule::class)->using(PivotDays\ThursdayDailyMeal::class);
    }

    public function fridaySchedules()
    {
        return $this->belongsToMany(WeekSchedule::class)->using(PivotDays\FridayDailyMeal::class);
    }

    public function saturdaySchedules()
    {
        return $this->belongsToMany(WeekSchedule::class)->using(PivotDays\SaturdayDailyMeal::class);
    }

    public function sundaySchedules()
    {
        return $this->belongsToMany(WeekSchedule::class)->using(PivotDays\SundayDailyMeal::class);
    }
}
