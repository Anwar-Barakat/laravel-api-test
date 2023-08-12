<?php

use App\Http\Controllers\Api\Client\ClientAuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'client'], function () {

    Route::controller(ClientAuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::get('profile', 'profile');
    });
});
