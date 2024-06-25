<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMeal extends Model
{
    use HasFactory;
    protected $table = 'daily_meals';
    protected $fillable = ['name', 'description', 'price'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }
}
