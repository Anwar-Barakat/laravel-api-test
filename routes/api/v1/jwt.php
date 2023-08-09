<?php

use App\Http\Controllers\Auth\JWTController;
use Illuminate\Support\Facades\Route;

Route::controller(JWTController::class)->group(function () {
    Route::post('login',                'login');
    Route::post('register',             'register');
    Route::post('logout',               'logout');
    Route::post('refresh',              'refresh');
});
