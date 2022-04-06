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

    Route::group(['prefix' => 'users'] , function ($router) {
        
        Route::post('login', 'UserController@login');

        Route::post('register', 'UserController@register');

        Route::get('profile', 'UserController@myProfile');
    });

    Route::group(['middleware' => 'auth:user'] , function ($router) {

        Route::group(['prefix' => 'orders'] , function ($router) {

            Route::get('', 'OrderController@index');

            Route::post('', 'OrderController@store');

            Route::post('mass-order', 'OrderController@massOrder');
        });
    
        Route::group(['prefix' => 'recharges'] , function ($router) {

            Route::get('{id}', 'RechargeController@show');
    
            Route::post('', 'RechargeController@store');
        });
    
        Route::group(['prefix' => 'likes'] , function ($router) {

            Route::get('', 'LikeController@index');
    
            Route::post('like/{id}', 'LikeController@store');
    
            Route::post('unlike/{id}', 'LikeController@destroy');
        }); 

        Route::group(['prefix' => 'technical-supports'] , function ($router) {
            Route::get('my-technical-support', 'TechnicalSupportController@show');

            Route::post('', 'TechnicalSupportController@store');
        }); 
    });

    Route::group(['prefix' => 'categories'] , function ($router) {
    
        Route::get('' , 'CategoryController@index');

        Route::get('/services' , 'CategoryController@categoryServices');

        Route::get('{id}' , 'CategoryController@show');
    });

    Route::group(['prefix' => 'services'] , function ($router) {
    
        Route::get('' , 'CategoryController@index');

        Route::get('/services' , 'CategoryController@categoryServices');

        Route::get('{id}' , 'ServiceController@show');

    });

    Route::group(['prefix' => 'admins'] , function ($router) {
        
        Route::post('login', 'AdminController@login');

        Route::post('register', 'AdminController@register');

        Route::get('profile', 'AdminController@myProfile');

        Route::group(['prefix' => 'categories'] , function ($router) {

            Route::post('' , 'CategoryController@store');

            Route::post('{id}/services' , 'ServiceController@store');
        });

    });
});
