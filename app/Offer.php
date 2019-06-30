<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'price',
        'date',
        'product_id'
    ];
//    protected  $appends = ['Product'];



    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function getProductAttribute(){

        return $this->product['name_ar'];
    }
}
