<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'meals';
    protected $fillable = ['meal_name_id', 'meal_mode_id', 'meal_items_id', 'price', 'meals_number', 'start_time', 'end_time'];

    public function mealName()
    {
        return $this->belongsTo(MealName::class);
    }

    public function mealMode()
    {
        return $this->belongsTo(MealMode::class);
    }

    public function mealItem()
    {
        return $this->belongsTo(MealItem::class);
    }

    public function mealRecords()
    {
        return $this->hasMany(MealRecord::class);
    }
}
