<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $table = 'users';
    protected $casts = [
        'affected_categories' => 'json',
        'editors' => 'json',
        'email_verified_at' => 'datetime',
    ];

    protected $fillable = ['category_id' ,'affected_categories', 'matriculation_number', 'creator', 'editors', 'name', 'email', 'phone_number', 'gender'];
    protected $hidden = ['password'];

    protected $guard_name = 'api';

    public function category()
    {
        return $this->belongsTo(UserCategory::class);
    }

    public function badge()
    {
        return $this->hasOne(Badge::class);
    }
}
