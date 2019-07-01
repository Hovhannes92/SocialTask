<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    const LIKE_DISLIKE = [
        'like' => 1,
        'dislike' => 2
    ];

    protected $fillable = [

        'user_id',
        'likeable_type',
        'likeable_id',
        'like_dislike'
    ];

    public function likeable()
    {
        return $this->morphTo();
    }
}
