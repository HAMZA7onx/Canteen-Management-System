<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealComponent extends Model
{
    protected $table = 'meal_components';
    protected $fillable = ['meal_menu_id', 'component_name', 'menu_name', 'description', 'base_price', 'is_required'];

    public function mealMenu()
    {
        return $this->belongsTo(MealMenu::class);
    }
}
