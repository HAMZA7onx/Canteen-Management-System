<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodComposant extends Model
{
    use HasFactory;
    protected $table = 'food_composants';
    protected $fillable = ['name', 'description'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_composant', 'food_composant_id', 'menu_id');
    }
}
