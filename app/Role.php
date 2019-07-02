<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
    ];

    public function posts()
    {
        return $this->belongsToMany('App\Post')->withTimestamps();
    }
}
