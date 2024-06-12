<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryDiscount extends Model
{
    use HasFactory;
    protected $table = 'category_discounts';
    protected $fillable = ['category_id', 'meal_schedule_id', 'meal_discount'];

    public function category()
    {
        return $this->belongsTo(UserCategory::class, 'category_id');
    }

    public function mealSchedule()
    {
        return $this->belongsTo(MealSchedule::class);
    }
}
