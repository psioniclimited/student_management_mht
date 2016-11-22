<?php

/*
  |--------------------------------------------------------------------------
  | Product Routes
  |--------------------------------------------------------------------------
  |
  | All the routes for Product module has been written here
  |
  |
 */
Route::group(['middleware' => ['web']], function () {
    Route::get('create_product', 'App\Modules\Product\Controllers\ProductController@addProduct');
    Route::post('create_product_process', 'App\Modules\Product\Controllers\ProductController@addProductProcess');
});

