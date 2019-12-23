<?php

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

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return "Welcome to Talio's API!";
    });

    Route::namespace('Admin')->group( function () {
        Route::post('/register', 'AuthController@register');
        Route::post('/login', 'AuthController@login');
        Route::post('/logout', 'AuthController@logout');
        Route::post('/refresh', 'AuthController@refresh');

        Route::middleware(['assign.guard:admin', 'jwt.auth'])->group(function () {
            Route::get('/profile/{admin}', 'ProfileController@get');
        });   
    });
    

});