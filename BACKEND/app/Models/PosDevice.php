<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosDevice extends Model
{
    use HasFactory;
    protected $table = 'pos_devices';

    protected $fillable = [
        'name',
        'ip_address',
        'print_statistics',
        'print_tickets',
        'status',
        'editors',
        'creator'
    ];

    protected $casts = [
        'editors' => 'json',
    ];

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }
}
