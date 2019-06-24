<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')->group(function(){
	//Auth Controller
	Route::post('/login','AuthController@login');
	Route::post('/socialLogin','AuthController@socialMediaLogin');
	Route::post('/register','AuthController@register');
	Route::get('/logout','AuthController@logout');

	Route::get('/getCountriesRegister','AuthController@getCountries');
	Route::get('/getCitiesRegister','AuthController@getCities');


	//ReserPassword Controller
	Route::post('/sendmail','ResetPasswordController@sendMail');
	Route::post('/checkcode','ResetPasswordController@checkCode');
	Route::post('/changepassword','ResetPasswordController@changePassword');



	Route::middleware('checkTokenOfUser')->group(function(){
		//HomeController
		Route::post('/getProducts','HomeController@getProducts');

		//VideoController
		Route::post('/getVideos','VideoController@getVideos');		
	});



});
