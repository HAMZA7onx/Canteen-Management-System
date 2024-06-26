<?php

namespace App\Models\PivotDays;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FridayDailyMeal extends Model
{
    use HasFactory;
    protected $table = 'friday_daily_meal';
    public $incrementing = true;

    protected $fillable = [
        'daily_meal_id',
        'week_schedule_id',
        'start_time',
        'end_time',
        'price'
    ];

    public function dailyMeal()
    {
        return $this->belongsTo(DailyMeal::class);
    }
}
