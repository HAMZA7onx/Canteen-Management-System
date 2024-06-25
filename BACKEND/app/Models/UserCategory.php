<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    protected $table = 'user_category';
    protected $fillable = ['name', 'description', 'editor'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
