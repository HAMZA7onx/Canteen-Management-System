<?php

namespace App\Models\Records;

use Illuminate\Database\Eloquent\Model;

class TuesdayRecord extends Model
{
    protected $table = 'tuesday_records';

    protected $fillable = [
        'tuesday_daily_meal_id',
        'badge_id',
    ];

    public function tuesdayDailyMeal()
    {
        return $this->belongsTo(\App\Models\TuesdayDailyMeal::class, 'tuesday_daily_meal_id');
    }

    public function badge()
    {
        return $this->belongsTo(\App\Models\Badge::class);
    }
}
