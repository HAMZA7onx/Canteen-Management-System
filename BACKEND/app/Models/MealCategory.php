<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealCategory extends Model
{
    protected $table = 'meal_categories';
    protected $fillable = ['name'];

    public function mealMenus()
    {
        return $this->hasMany(MealMenu::class);
    }
}
