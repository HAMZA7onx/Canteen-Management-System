<?php

namespace App\Models\Records;

use Illuminate\Database\Eloquent\Model;

class ThursdayRecord extends Model
{
    protected $table = 'thursday_records';

    protected $fillable = [
        'thursday_daily_meal_id',
        'badge_id',
    ];

    public function thursdayDailyMeal()
    {
        return $this->belongsTo(ThursdayDailyMeal::class, 'thursday_daily_meal_id');
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}
