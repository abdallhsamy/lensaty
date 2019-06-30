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


	//recently Added By Abdallah Samy

    Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
    Route::get('login/{provider}/callback','Auth\LoginController@handleProviderCallback');



    Route::group(['prefix' => 'profile'], function () {

        Route::get('/', [
            'uses' => 'ProfileController@profile',
            'as'   => 'profile'
        ]);

        Route::get('{profile}', [
            'uses' => 'ProfileController@show',
            'as'   => 'profile.show'
        ]);
        /*
        Route::get('{profile}/edit', [
            'uses' => 'ProfileController@edit',
            'as'   => 'profile.edit'
        ]);

        Route::put('{profile}', [
            'uses' => 'ProfileController@update',
            'as'   => 'profile.update'
        ]);*/
//        Route::get('create', [
//            'uses' => 'ProfileController@create',
//            'as'   => 'profile.create'
//        ]);
//
//        Route::post('/', [
//            'uses' => 'ProfileController@store',
//            'as'   => 'profile.store'
//        ]);

//        Route::get('/{service}/delete', [
//            'uses' => 'ServiceConProfileControllertroller@destroy',
//            'as'   => 'adminprofileroy'
//        ]);
    });



    Route::group(['prefix' => 'brands'], function () {

        Route::get('/', [
            'uses' => 'BrandController@index',
            'as'   => 'brands'
        ]);

        Route::get('{brand}', [
            'uses' => 'BrandController@show',
            'as'   => 'brand.show'
        ]);

    });



    Route::group(['prefix' => 'offers'], function () {

        Route::get('/', [
            'uses' => 'OfferController@index',
            'as'   => 'offers'
        ]);

        Route::get('{offer}', [
            'uses' => 'OfferController@show',
            'as'   => 'offer.show'
        ]);

    });



});
