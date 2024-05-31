<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealSchedule extends Model
{
    protected $table = 'meal_schedules';
    protected $fillable = ['meal_menu_id', 'start_time', 'end_time', 'persons_taken'];

    public function mealMenu()
    {
        return $this->belongsTo(MealMenu::class);
    }

    public function mealRecords()
    {
        return $this->hasMany(MealRecord::class);
    }
}
