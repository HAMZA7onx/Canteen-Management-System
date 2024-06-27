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
    protected $fillable = ['mode_name', 'description', 'creator', 'editors'];

    public function mondayDailyMeals()
    {
        return $this->belongsToMany(DailyMeal::class, MondayDailyMeal::class)
            ->withPivot('start_time', 'end_time', 'price');
    }

    public function tuesdayDailyMeals()
    {
        return $this->belongsToMany(DailyMeal::class, TuesdayDailyMeal::class)
            ->withPivot('start_time', 'end_time', 'price');
    }

    public function wednesdayDailyMeals()
    {
        return $this->belongsToMany(DailyMeal::class, WednesdayDailyMeal::class)
            ->withPivot('start_time', 'end_time', 'price');
    }

    public function thursdayDailyMeals()
    {
        return $this->belongsToMany(DailyMeal::class, ThursdayDailyMeal::class)
            ->withPivot('start_time', 'end_time', 'price');
    }

    public function fridayDailyMeals()
    {
        return $this->belongsToMany(DailyMeal::class, FridayDailyMeal::class)
            ->withPivot('start_time', 'end_time', 'price');
    }

    public function saturdayDailyMeals()
    {
        return $this->belongsToMany(DailyMeal::class, SaturdayDailyMeal::class)
            ->withPivot('start_time', 'end_time', 'price');
    }

    public function sundayDailyMeals()
    {
        return $this->belongsToMany(DailyMeal::class, SundayDailyMeal::class)
            ->withPivot('start_time', 'end_time', 'price');
    }
}
