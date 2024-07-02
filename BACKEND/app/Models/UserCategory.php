<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    protected $table = 'user_category';
    protected $casts = [
        'editors' => 'json',
    ];
    protected $fillable = ['name', 'description', 'creator', 'editors'];
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
