<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'App\Http\Controllers\API'], function ($router) {

    Route::group(['prefix' => 'user'] , function ($router) {
        
        Route::post('login', 'UserController@login');

        Route::post('register', 'UserController@register');

        Route::get('my-cart', 'CartController@show');

        Route::post('cart/add/{id}', 'CartController@addToCart');

        Route::delete('cart/remove/{id}', 'CartController@removeCartItem');
    });

    Route::group(['prefix' => 'order'] , function ($router) {
        
        Route::post('place-order', 'OrderController@store');

        Route::post('register', 'UserController@register');

        Route::get('my-cart', 'CartController@show');

        Route::post('cart/add/{id}', 'CartController@addToCart');

        Route::delete('cart/remove/{id}', 'CartController@removeCartItem');
    });

    Route::group(['prefix' => 'admin'] , function ($router) {
        
        Route::post('login', 'AdminController@login');

        Route::post('register', 'AdminController@register');

        // Route::get('requested-guardians', 'UserController@getRequestedGuardians');

        // Route::post('accept-guardians/{id}', 'UserController@acceptdGuardian');

        Route::group(['prefix' => 'category'] , function ($router) {

            Route::post('' , 'CategoryController@store');

            Route::post('{id}/service' , 'ServiceController@store');
        });


    });
});
