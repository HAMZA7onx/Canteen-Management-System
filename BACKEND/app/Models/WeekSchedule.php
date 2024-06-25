<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeekSchedule extends Model
{
    use HasFactory;
    protected $table = 'week_schedule';
    protected $fillable = ['mode_name', 'description', 'editor'];
}
