<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersSocial extends Model
{
    protected $fillable = ['access_token','provider','user_id'];

    public function User(){
    	return $this->belongsTo('App\User');
    }
}
