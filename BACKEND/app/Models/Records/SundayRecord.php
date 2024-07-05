<?php

namespace App\Models\Records;

use Illuminate\Database\Eloquent\Model;

class SundayRecord extends Model
{
    protected $table = 'sunday_records';

    protected $fillable = [
        'sunday_daily_meal_id',
        'badge_id',
    ];

    public function sundayDailyMeal()
    {
        return $this->belongsTo(SundayDailyMeal::class, 'sunday_daily_meal_id');
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}
