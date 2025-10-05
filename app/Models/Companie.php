<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Companie extends Model
{
    protected $fillable = ['name', 'email', 'phone'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}