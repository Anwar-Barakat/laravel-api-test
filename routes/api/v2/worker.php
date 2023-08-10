<?php

use App\Http\Controllers\Api\Worker\WorkerAuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'worker'], function () {

    Route::controller(WorkerAuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::get('profile', 'profile');
    });
});
