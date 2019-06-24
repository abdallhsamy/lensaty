<?php

namespace App\Http\Controllers\Api;

use DB;
use App\User;
use App\UsersSocial;
use App\Country;
use App\City;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{


    public function login(Request $request){
  		$validator = Validator::make($request->all(),[
       'password'=>'required|string|min:8'
     ]);
     if($validator->fails()) {
      // return '{"code":"400","message":"Error","data":' . '[' . json_encode($validator->errors()) . ']}';
     	return '{"code":"400","message":' . '[' . json_encode($validator->errors()) . ']}';
     }

    // $lang = isset($request->lang) ? $request->lang : 'en';
    
	    $email= isset($request->email) ? $request->email : '';
	    $username = isset($request->username) ? $request->username : '';
	    $password=$request->password;
	    $remember_me = isset($request->remember_me) ? $request->remember_me : NULL;


	    $user = DB::table('users')->where('email', $email)->first();
	    $user1 = DB::table('users')->where('username', $username)->first();
	    if($user!=null){
	        if(password_verify($password, $user->password)) {
	            //check for token
	            $userID = $user->id;
	            $userCheckToken = UsersSocial::where('user_id',$userID)->first();
	            if($userCheckToken == NULL){
	                $userSocial = UsersSocial::create([
	                    'access_token'=>Str::random(60),
	                    'provider'=>'web',
	                    'user_id'=>$userID
	                ]);
	                $user = User::where('id',$userID)->with(['UsersSocial'=>function($query){
	                  $query->select('users_socials.access_token','users_socials.user_id');
	                }])->select('users.id')->get();
	            }else{
	                $user = User::where('id',$userID)->with(['UsersSocial'=>function($query){
	                  $query->select('users_socials.access_token','users_socials.user_id');
	                }])->select('users.id')->get();
	                // $user = User::with('UsersSocial')->with('Country')->with('City')->find($userID);
	            }

	            User::where('id',$userID)->update(['remember_token'=>Str::random(20)]);

	            return '{"code":"200","message":"Success","data":'.json_encode($user).'}';
	       }else{
	            $error = 'Email or Password is incorrect';
	            // return '{"code":"400","message":"Error","data":'.'['.json_encode($error).']}';
	            return '{"code":"400","message":'.'['.json_encode($error).']}';
	       }  
	    }

	    elseif($user1!=null){
	        if(password_verify($password, $user1->password)) {

	            //check for token
	            $userID = $user1->id;
	            $userCheckToken = UsersSocial::where('user_id',$userID)->first();
	            if($userCheckToken == NULL){
	                $userSocial = UsersSocial::create([
	                    'access_token'=>Str::random(60),
	                    'provider'=>'web',
	                    'user_id'=>$userID
	                ]);
	                // $user1 = User::with('UsersSocial')->with('Country')->with('City')->find($userID);
	                   $user1 = User::where('id',$userID)->with(['UsersSocial'=>function($query){
	                  $query->select('users_socials.access_token','users_socials.user_id');
	                }])->select('users.id')->get();
	            }else{
	                   $user1 = User::where('id',$userID)->with(['UsersSocial'=>function($query){
	                  $query->select('users_socials.access_token','users_socials.user_id');
	                }])->select('users.id')->get();
	                // $user1 = User::with('UsersSocial')->with('Country')->with('City')->find($userID);
	            }
	            User::where('id',$userID)->update(['remember_token'=>Str::random(20)]);
	            return '{"code":"200","message":"Success","data":'.json_encode($user1).'}';
	       }else{
	            $error = 'Username or Password is incorrect';
	            return '{"code":"400","message":'.'['.json_encode($error).']}';
	       }  
	    }

	    else{
	        $error = 'Invalid Credentials';
	        return '{"code":"400","message":'.'['.json_encode($error).']}';
	    }
    }

   public function socialMediaLogin(Request $request){

     $validator = Validator::make($request->all(),[
       'access_token'=>'required|string',
       'provider'=>'required|string',
       'email'=>'required|email',
         'first_name'=>'required|string',
       'last_name'=>'required|string',
     ]);
     if($validator->fails()) {
       return '{"code":"400","message":' . '[' . json_encode($validator->errors()) . ']}';
     }

    $token = $request->access_token;
    $provider = $request->provider;
    $email = $request->email;
  // $user = UsersSocial::where('access_token',$token)->where('provider',$provider)->first();
  $userId = User::where('email',$email)->value('id');
  $user = UsersSocial::where('user_id',$userId)->where('provider',$provider)->first();
     //if user exist
     if($user){
         $userId = $user->user_id;

           $user = User::where('id',$userId)->with(['UsersSocial'=>function($query){
             $query->select('users_socials.access_token','users_socials.user_id');
           }])->select('users.id')->get();

         return '{"code":"200","message":"Success","data":'.json_encode($user).'}';
     }

  if(empty($user) && $userId){
       $error = 'Email has been taken';
       return '{"code":"400","message":'.'['.json_encode($error).']}';
  }


     //if user not exist
     if(empty($user) && $userId == null){
         $newUser = User::create([
             'first_name' => $request->first_name,
             'last_name' => $request->last_name,
             // 'username' => $request->username,
             'email' => $request->email,
             //'password' => Hash::make($request->password),
             'profile_picture' => url('/images/userProfilePictures/default.png'),
         ]);
         $userSocial = UsersSocial::create([
             'access_token'=>$request->access_token,
             'provider'=>$request->provider,
             'user_id'=>$newUser->id
         ]);
         //$user = User::with('usersSocial')->with('Country')->with('City')->find($newUser->id);
           $user = User::where('id',$newUser->id)->with(['UsersSocial'=>function($query){
             $query->select('users_socials.access_token','users_socials.user_id');
           }])->select('users.id')->get();
         return '{"code":"200","message":"Success","data":'.json_encode($user).'}';
  }
   }

    public function register(Request $request){
	    $validator = Validator::make($request->all(),[
	      'first_name'=>'required|string',
	      'last_name'=>'required|string',
	      // 'username'=>'required|string|unique:users',
	      'email'=>'required|email|unique:users',
	      'password'=>'required|string|min:8',
	      'confirm_password'=>'required|string|min:8|same:password',
	      'date_of_birth'=>'required',
	      'mobile'=>'required|integer',
	      'gender'=>'required',
	      // 'city_id'=>'required|integer',
	      // 'country_id'=>'required|integer'
	     ]);

	     if($validator->fails()){
	   return '{"code":"400","message":' . '[' . json_encode($validator->errors()) . ']}';
	     }
	     $password=Hash::make($request->password);

	       $user = User::create([
	       'first_name'=>$request->first_name,
	       'last_name'=>$request->last_name,
	       // 'username'=>$request->username,
	       'email' => $request->email,
	       'password' => $password,
	       'date_of_birth'=>$request->date_of_birth,
	       'mobile'=>$request->mobile,
	       'gender'=>$request->gender,
	       'profile_picture' => url('/images/userProfilePictures/default.png'),
	       // 'city_id'=>$request->city_id,
	       // 'country_id'=>$request->country_id
	       ]);

	       //create token for user while register
	       $userSocial = UsersSocial::create([
	       'access_token'=>Str::random(60),
	       'provider'=>'web',
	       'user_id'=>$user->id
	       ]);

	       // $user = User::with('UsersSocial')->find($user->id);
	        $user = User::where('id',$user->id)->with(['UsersSocial'=>function($query){
	         $query->select('users_socials.access_token','users_socials.user_id');
	        }])->select('users.id')->get();

	       if($user==null){
	        $error = 'Error';
	    return '{"code":"400","message":'.'['.json_encode($error).']}';
	       }else{
	      return '{"code":"200","message":"Success","data":'.json_encode($user).'}';
	     }
    }

    public function logout(Request $request){
     $validator = Validator::make($request->all(),[
       'token'=>'required',
       'user_id'=>'required|integer'
     ]);
     if($validator->fails()) {
       return '{"code":"400","message":' . '[' . json_encode($validator->errors()) . ']}';
     }
     $token = $request->token;
     $user_id = $request->user_id;

        $checkUserToken = UsersSocial::where('access_token',$token)->where('user_id',$user_id)->first();
        if($checkUserToken){
            $socialLogin = UsersSocial::where('access_token',$token)->where('user_id',$user_id)->delete();
         $success = 'Logout Successfully';
         return '{"code":"200","message":'.'['.json_encode($success).']}';
        }else{
         $error = 'Invalid token or user id';
         return '{"code":"400","message":'.'['.json_encode($error).']}';
        }
    }

   public function getCountries(Request $request){
     $validator = Validator::make($request->all(),[
       'lang'=>'required|string|max:2|in:en,ar',
     ]);
     if($validator->fails()) {
       return '{"code":"400","message":' . '[' . json_encode($validator->errors()) . ']}';
     }
     $lang = $request->lang ? $request->lang : 'en';
     $countries = Country::get(['id','country_name_'.$lang.' as country_name','code','created_at','updated_at']);
     if(count($countries)>0){
       return '{"code":"200","message":"Success","data":'.json_encode($countries).'}';  
     }else{
       $error = 'There is no countries';
       return '{"code":"400","message":'.'['.json_encode($error).']}';
     }
   }

   public function getCities(Request $request){
     $validator = Validator::make($request->all(),[
       'lang'=>'required|string|max:2|in:en,ar',
       'country_id'=>'required|integer'
     ]);
     if($validator->fails()) {
       return '{"code":"400","message":' . '[' . json_encode($validator->errors()) . ']}';
     }

     $lang = $request->lang ? $request->lang : 'en';
     $country_id = $request->country_id;
     $cities = City::where('country_id',$country_id)->get(['id','city_name_'.$lang.' as city_name','code','created_at','updated_at']);

     if(count($cities)>0){
       return '{"code":"200","message":"Success","data":'.json_encode($cities).'}';  
     }else{
       $error ='There is no cities';
       return '{"code":"400","message":'.'['.json_encode($error).']}';
     }
   }

}
