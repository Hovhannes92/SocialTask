<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatUserAction extends Model
{
    protected $fillable = [
        'user_id',
        'chat_id',
        'action_date'
    ];
}
