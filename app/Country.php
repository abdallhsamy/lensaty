<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'country_name_ar', 'country_name_en', 'code',
    ];

    public function User(){
        return $this->hasOne('App\User');
    }
}
