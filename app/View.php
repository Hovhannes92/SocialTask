<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'view_count'
    ];

    public function post()
    {
        return $this->belongsTo('App\Post')->withTimestamps();
    }
}
