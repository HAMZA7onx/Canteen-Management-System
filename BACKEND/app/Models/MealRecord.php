<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealRecord extends Model
{
    use HasFactory;

    protected $table = 'meal_records';
    protected $fillable = ['badge_id', 'meal_id', 'price_paid', 'taken_at'];

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
