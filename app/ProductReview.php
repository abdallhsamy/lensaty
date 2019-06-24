<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable=['rate','product_id','user_id'];

    public function User(){
        return belongsTo('App\User');
    }

    public function Product(){
        return belongsTo('App\Product');
    }
}
