<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'products'],function (){
    Route::controller(ProductController::class)->group(function (){

        Route::get('/',                 'index');
        Route::post('/',                'store');
        Route::get('/{product}',        'show')->whereNumber('product');
        Route::put('/{product}',        'update')->whereNumber('product');
        Route::delete('/{product}',     'destroy')->whereNumber('product');
    });
});
