<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealSchedule extends Model
{
    protected $table = 'meal_schedules';
    protected $fillable = ['meal_menu_id', 'meal_name_id', 'date', 'start_time', 'end_time', 'persons_taken'];

    public function mealMenu()
    {
        return $this->belongsTo(MealMenu::class);
    }

    public function mealName()
    {
        return $this->belongsTo(MealName::class);
    }

    public function mealRecords()
    {
        return $this->hasMany(MealRecord::class);
    }

    public function categoryDiscounts()
    {
        return $this->hasMany(CategoryDiscount::class);
    }
}
