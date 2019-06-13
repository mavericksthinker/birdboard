<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    public function projects(){

        // As it will look for user_id as default in the projects table so we have to overwrite it to owner_id as we set
        // return $this->hasMany(Project::class,'owner_id')->orderBy('updated_at','desc');
        // Or
        // return $this->hasMany(Project::class,'owner_id')->orderByDesc('updated_at');
        // Or latest is default to created_at column in desc order and latest is default to created_at column in asc order
         return $this->hasMany(Project::class,'owner_id')->latest('updated_at');

    }
}
