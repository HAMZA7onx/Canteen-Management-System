<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Badge extends Model
{
    protected $table = 'badges';
    protected $casts = [
        'editors' => 'json',
    ];
    protected $fillable = ['user_id', 'rfid', 'status', 'creator', 'editors'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
