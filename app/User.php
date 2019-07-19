<?php

namespace App;

use App\Events\UserSaving;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const ROLES = [
        'Admin' => 1,
        'User' => 2
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saving' => UserSaving::class,
    ];

    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'picture', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//    public function hasRole($role)
//    {
//        return User::where('role', $role)->get();
//    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function chats()
    {
        return $this->belongsToMany('App\Chat')->withTimestamps();
    }

}
