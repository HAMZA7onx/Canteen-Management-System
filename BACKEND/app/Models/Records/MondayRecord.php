<?php

namespace App\Models\Records;

use Illuminate\Database\Eloquent\Model;

class MondayRecord extends Model
{
    protected $table = 'monday_records';

    protected $fillable = [
        'monday_daily_meal_id',
        'badge_id',
    ];

    public function mondayDailyMeal()
    {
        return $this->belongsTo(MondayDailyMeal::class, 'monday_daily_meal_id');
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}
