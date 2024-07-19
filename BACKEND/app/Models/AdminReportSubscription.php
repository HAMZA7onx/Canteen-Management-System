<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminReportSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['admin_id', 'frequency', 'is_active'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
