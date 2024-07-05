<?php

namespace App\Models\Records;

use Illuminate\Database\Eloquent\Model;

class SaturdayRecord extends Model
{
    protected $table = 'saturday_records';

    protected $fillable = [
        'saturday_daily_meal_id',
        'badge_id',
    ];

    public function saturdayDailyMeal()
    {
        return $this->belongsTo(SaturdayDailyMeal::class, 'saturday_daily_meal_id');
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}
