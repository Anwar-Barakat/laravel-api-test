<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'users'
], function () {
    Route::get('/',                     [UserController::class, 'index']);
    Route::get('/{user}',               [UserController::class, 'show'])->whereNumber('user');
    Route::post('/',                    [UserController::class, 'store']);
    Route::put('/{user}',               [UserController::class, 'update'])->whereNumber('user');
    Route::delete('/{user}',            [UserController::class, 'destroy'])->whereNumber('user');
});
