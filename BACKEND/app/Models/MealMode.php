<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealMode extends Model
{
    use HasFactory;

    protected $table = 'meal_modes';
    protected $fillable = ['mode'];

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
