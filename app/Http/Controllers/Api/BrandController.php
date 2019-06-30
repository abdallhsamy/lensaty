<?php

namespace App\Http\Controllers\Api;

use App\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function index(Request $request){
        $lang = $request->lang ? $request->lang : 'en';
        $brands = Brand::get(['brands.id','brands.name_'.$lang.' as name','brands.image']);

        return response([
           'status'         =>      200,
           'message'        =>      'Success',
           'data'           =>      $brands
        ]);
    }
    public function show(Request $request,Brand $brand){
        $brand = Brand::find($request->id);
        if ($request->lang == 'en') {
            $name = $brand->name_en;
        } else {
            $name = $brand->name_ar;
        }

//        ($request->lang == 'ar' ? );
        return response([
            'status'         =>      200,
            'message'        =>      'Success',
            'name'           =>      $name,
            'image'          =>      $brand->image
        ]);
    }
}
