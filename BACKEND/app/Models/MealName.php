<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealName extends Model
{
    use HasFactory;

    protected $table = 'meal_names';
    protected $fillable = ['name'];

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
