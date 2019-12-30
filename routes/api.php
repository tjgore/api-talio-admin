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

        Route::get('/profile/{admin}', 'ProfileController@get');  
    });
    
    Route::get('products', 'ProductController@getAll');
    Route::get('{store}', 'StoreController@get');
    Route::get('products/{product}', 'ProductController@getDetails');
    Route::get('{store}/products', 'ProductController@getBusinessProducts');

    Route::middleware(['jwt-auth:admin'])->group(function () {
        Route::post('/logout', 'Admin\\AuthController@logout');
        Route::post('/refresh', 'Admin\\AuthController@refresh');

        Route::put('profile/update', 'Admin\\ProfileController@update');

        Route::post('store/create', 'StoreController@create');
        Route::post('{store}/upload-logo', 'StoreController@uploadLogo');
        Route::put('{store}/update', 'StoreController@update');
        Route::post('{store}/social-media', 'StoreController@putSocialMedia');

        Route::middleware(['belongsTo'])->group(function () {
            Route::post('products/create', 'ProductController@create');
            Route::put('products/{product}/edit', 'ProductController@update');
            Route::post('products/image', 'ProductController@uploadImage');
            Route::post('products/{product}/images', 'ProductController@uploadGallery');

            Route::post('products/{product}/spec', 'SpecificationController@updateOrCreate');
            Route::delete('products/{product}/spec/{specification}', 'SpecificationController@delete');

            Route::get('orders', 'OrderController@getAll');
            Route::get('orders/{order}', 'OrderController@getProductOrderDetails');
            Route::post('orders', 'OrderController@create');
        });
    }); 

});

Route::fallback(function () {
    return response()->json([
        'message' => '404 Page Not Found!' 
    ], 404);
});