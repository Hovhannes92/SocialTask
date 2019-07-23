<?php

namespace App;

use App\Events\MessageCreated;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $dispatchesEvents = [
        'created' => MessageCreated::class,
    ];

    protected $fillable = [
       'user_id',
       'message',
        'chat_id',
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
