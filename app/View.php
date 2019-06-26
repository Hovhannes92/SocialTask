<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $fillable = [
        'count',
        'post_id',
    ];

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

}
