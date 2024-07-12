<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminBadge extends Model
{
    protected $table = 'admins_badges';
    protected $casts = [
        'editors' => 'json',
    ];
    protected $fillable = ['admin_id', 'rfid', 'status', 'creator', 'editors'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
