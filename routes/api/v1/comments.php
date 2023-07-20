<?php

use App\Http\Controllers\Api\CommentController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'comments'
], function () {
    Route::get('/',                     [CommentController::class, 'index']);
    Route::get('/{comment}',            [CommentController::class, 'show'])->whereNumber('comment');
    Route::post('/',                    [CommentController::class, 'store']);
    Route::put('/{comment}',            [CommentController::class, 'update'])->whereNumber('comment');
    Route::delete('/{comment}',         [CommentController::class, 'destroy'])->whereNumber('comment');
});
