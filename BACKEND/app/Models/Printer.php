<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ip_address',
        'creator',
        'editors',
    ];

    protected $casts = [
        'editors' => 'array',
    ];

    public function posDevices()
    {
        return $this->belongsToMany(PosDevice::class, 'pos_printer_links');
    }
}
