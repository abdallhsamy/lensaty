<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'city_name_en', 'city_name_ar', 'code','country_id',
    ];


    public function User(){
        return $this->hasOne('App\User');
    }
}
