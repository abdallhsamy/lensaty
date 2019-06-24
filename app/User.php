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
        'first_name','last_name','username', 'email', 'password','date_of_birth','mobile','gender','profile_picture','city_id','country_id'
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

    public function UsersSocial(){
        return $this->hasOne('App\UsersSocial');
    }

    public function ProductReview(){
        return $this->hasMany('App\ProductReview');
    }

    public function Country(){
        return $this->belongsTo('App\Country');
    }

    public function City(){
        return $this->belongsTo('App\City');
    }
}
