<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealRecord extends Model
{
    protected $table = 'meal_records';
    protected $fillable = ['badge_id', 'meal_schedule_id', 'price_paid', 'selected_components', 'taken_at'];

    protected $casts = [
        'selected_components' => 'array',
    ];

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    public function mealSchedule()
    {
        return $this->belongsTo(MealSchedule::class);
    }
}
