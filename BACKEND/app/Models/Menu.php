<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menu';
    protected $fillable = ['name', 'description', 'image'];

    public function foodComposants()
    {
        return $this->belongsToMany(FoodComposant::class, 'menu_composant', 'menu_id', 'food_composant_id');
    }
}
