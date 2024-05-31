<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    protected $table = 'user_category';
    protected $fillable = ['category', 'meal_discount'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
