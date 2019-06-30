<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

class ProfileController extends Controller
{
    public function profile(Request $request, User $user){
//        $user = $request->user_id;
        $user =User::find($request->user_id);
//        dd($user);
        return response([

            'status'                        =>      200,
            'message'                       =>      'Success',
            'first_name'                    =>      $user->first_name,
            'last_name'                     =>      $user->last_name,
            'username'                      =>      $user->username,
            'email'                         =>      $user->email,
            'date_of_birth'                 =>      $user->date_of_birth,
            'mobile'                        =>      $user->mobile,
            'gender'                        =>      $user->gender,
            'profile_picture'               =>      $user->profile_picture,
            'city_id'                       =>      $user->city_id,
            'country_id'                    =>      $user->country_id,
        ]);
    }
//    public function show(User $user){
//        return response([
//
//            'status'                        =>      200,
//            'first_name'                    =>      $user->first_name,
//            'last_name'                     =>      $user->last_name,
//            'username'                      =>      $user->username,
//            'email'                         =>      $user->email,
//            'date_of_birth'                 =>      $user->date_of_birth,
//            'mobile'                        =>      $user->mobile,
//            'gender'                        =>      $user->gender,
//            'profile_picture'               =>      $user->profile_picture,
//            'city_id'                       =>      $user->city_id,
//            'country_id'                    =>      $user->country_id,
//        ]);
//    }
}
