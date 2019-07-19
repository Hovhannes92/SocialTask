<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
       'user_id',
       'message',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function chat()
    {
        return $this->belongsTo('App\Chat');
    }

}
