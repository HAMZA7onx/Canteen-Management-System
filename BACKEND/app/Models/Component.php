<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $table = 'components';
    protected $fillable = ['component_name', 'description', 'base_price', 'is_required'];

    public function mealMenus()
    {
        return $this->belongsToMany(MealMenu::class, 'menu_component');
    }
}
