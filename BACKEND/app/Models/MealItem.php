<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealItem extends Model
{
    use HasFactory;

    protected $table = 'meal_items';
    protected $fillable = ['menu'];

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
