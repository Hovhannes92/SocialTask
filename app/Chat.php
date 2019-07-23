<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    const TYPES = [
        'private' => 1,
        'group' => 2
    ];


    protected $fillable = [
        'type',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('action_date')->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }
}
