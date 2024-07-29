<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosDevice extends Model
{
    use HasFactory;
    protected $table = 'pos_devices';
    protected $casts = [
        'editors' => 'json',
    ];

    protected $fillable = [
        'name',
        'ip_address',
        'status',
        'editors',
        'creator'
    ];
}
