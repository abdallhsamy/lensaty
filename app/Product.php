<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    
	protected $fillable = [
		'name_en','name_ar','description_en','description_ar',
		'price','image','delivery_fees','delivery_period','brand_id'
	];

    public function Brand(){
    	return $this->belongsTo('App\Brand');
    }

    public function ProductReview(){
        return $this->hasMany('App\ProductReview');
    }
}
