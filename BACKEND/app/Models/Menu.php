<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menu';
    protected $fillable = ['name', 'description'];

    public function dailyMeals()
    {
        return $this->belongsToMany(DailyMeal::class, 'daily_meal_menu');
    }

    public function foodComposants()
    {
        return $this->belongsToMany(FoodComposant::class, 'menu_composant', 'menu_id', 'food_composant_id');
    }
}
