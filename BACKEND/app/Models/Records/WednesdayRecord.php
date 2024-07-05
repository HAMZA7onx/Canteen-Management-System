<?php

namespace App\Models\Records;

use Illuminate\Database\Eloquent\Model;

class WednesdayRecord extends Model
{
    protected $table = 'wednesday_records';

    protected $fillable = [
        'wednesday_daily_meal_id',
        'badge_id',
    ];

    public function wednesdayDailyMeal()
    {
        return $this->belongsTo(WednesdayDailyMeal::class, 'wednesday_daily_meal_id');
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}
