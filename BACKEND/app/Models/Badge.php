<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $table = 'badges';
    protected $fillable = ['user_id', 'rfid', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mealRecords()
    {
        return $this->hasMany(MealRecord::class);
    }
}
