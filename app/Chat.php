<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'type',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }
}
