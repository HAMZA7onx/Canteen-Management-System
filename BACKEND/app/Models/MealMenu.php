<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealMenu extends Model
{
    protected $table = 'meal_menus';
    protected $fillable = ['meal_category_id', 'menu_name', 'price'];

    public function mealCategory()
    {
        return $this->belongsTo(MealCategory::class);
    }

    public function mealComponents()
    {
        return $this->hasMany(MealComponent::class);
    }

    public function mealSchedules()
    {
        return $this->hasMany(MealSchedule::class);
    }
}
