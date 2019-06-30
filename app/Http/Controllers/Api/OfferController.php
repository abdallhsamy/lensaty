<?php

namespace App\Http\Controllers\Api;

use App\Offer;
//use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $offers     = Offer::all()->load('product');
//        $offers     = Offer::with('product')->get();
//        dd($offers);
//        $offers = DB::table('offers') ->join('products', 'offers.product_id', '=', 'products.id')->get();
        $lang = $request->lang ? $request->lang : 'en';

        return response([
            'status'        =>  200,
            'message'       =>  'Success',
            'offers'        =>  $offers,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Request $request,Offer $offer)
    {
        $offer = Offer::find($request->offer)->load('product');

//        $product    =   $offer;
//        dd($product);
        return response([
            'status'    =>200,
            'message'   =>'Success',
            'offer'     =>$offer,
//            'product'   =>$product
        ]);
    }

    public function edit(Offer $offer)
    {
        //
    }


    public function update(Request $request, Offer $offer)
    {
        //
    }

    public function destroy(Offer $offer)
    {
        //
    }
}
