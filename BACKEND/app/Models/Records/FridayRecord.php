<?php

namespace App\Models\Records;

use Illuminate\Database\Eloquent\Model;

class FridayRecord extends Model
{
    protected $table = 'friday_records';

    protected $fillable = [
        'friday_daily_meal_id',
        'badge_id',
    ];

    public function fridayDailyMeal()
    {
        return $this->belongsTo(FridayDailyMeal::class, 'friday_daily_meal_id');
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}
