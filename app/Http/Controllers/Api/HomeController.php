<?php

namespace App\Http\Controllers\Api;

use Validator;
use DB;
use App\Product;
use App\ProductReview;
use App\Advertisement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function getProducts(Request $request){
        $validator = Validator::make($request->all(),[
          'lang'=>'required|string|max:2|in:en,ar',
        ]);
        if($validator->fails()) {
//          return '{"code":"400","message":' . '[' . json_encode($validator->errors()) . ']}';
            return response([
                'status'    =>400,
                'message'   =>implode(",",$validator->errors()->all()),
            ]);
        }
    	$token = $request->token;
    	$lang = $request->lang ? $request->lang : 'en';

    	$search = isset($request->search) ? $request->search : '';
    	// $brand_id = isset($request->brand_id) ? $request->brand_id : '';
		// $price = isset($request->price) ? $request->price : '';
		// $rate = isset($request->rate) ? $request->rate : '';

        $home = array();

        $advertisement = Advertisement::get(['advertisements.id','advertisements.image_'.$lang.' as image']);



    	$products = Product::select(
    		'products.id','products.name_'.$lang.' as name','products.image','products.price',

			DB::raw("(SELECT SUM(product_reviews.rate) / COUNT(product_reviews.rate) FROM product_reviews WHERE product_reviews.product_id = products.id GROUP BY product_reviews.product_id) as product_rate"));


    	// if($brand_id){
    	// 	$products->where('products.brand_id','=',$brand_id);
    	// }
    	// if($price){
    	// 	$products->where('products.price','>=',$price);
    	// }
    	// if($rate){
    	// 	$products->having('product_rate','>=',$rate);
    	// }

    	if($search != ''){
    		$products->where('products.name_en','like','%'.$search.'%')
				->orWhere('products.name_ar','like','%'.$search.'%');
    	}

        $products = $products->orderBy('products.created_at','DESC')->get();

        $home['advertisement'] = $advertisement;
        $home['new_arrival'] = $products;


        if(count($home)>0){
//            return '{"code":"200","message":"Success","data":'.json_encode($home).'}';
            return response([
                'status'    =>200,
                'message'   =>'Success',
                'data'      =>$home,
            ]);
        }else{
            $error = 'There is no data';
//            return '{"code":"400","message":'.'['.json_encode($error).']}';
            return response([
                'status'    =>400,
                'message'   =>$error,
            ]);
        }

    }
}
