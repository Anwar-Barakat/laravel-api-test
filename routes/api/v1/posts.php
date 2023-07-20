<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'posts'
], function () {
    Route::get('/',                     [PostController::class, 'index']);
    Route::get('/{post}',               [PostController::class, 'show'])->whereNumber('post');
    Route::post('/',                    [PostController::class, 'store']);
    Route::put('/{post}',               [PostController::class, 'update'])->whereNumber('post');
    Route::delete('/{post}',                [PostController::class, 'destroy'])->whereNumber('post');
});