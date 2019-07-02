<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'user_id',
        'view_count'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function views()
    {
        return $this->hasMany('App\View');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }

    public function likes()
    {
        return $this->morphMany('App\Like', 'likeable');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role')->withTimestamps();
    }
}
