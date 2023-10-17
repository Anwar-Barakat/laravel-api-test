<?php

use App\Http\Controllers\Api\Admin\AdminAuthController;
use App\Http\Controllers\Api\Admin\AdminNotificationController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {

    Route::controller(AdminAuthController::class)->group(function () {
        Route::post('login',        'login');
        Route::post('register',     'register');
        Route::post('logout',       'logout');
        Route::post('refresh',      'refresh');
        Route::get('profile',       'profile');
    });

    Route::controller(AdminNotificationController::class)->middleware(['auth:admin'])->prefix('notifications')->group(function () {
        Route::get('/',                 'index');
        Route::get('/unread',           'unread');
        Route::post('/markRead',        'markRead');

        Route::delete('/',          'delete');
        Route::delete('/deleteAll',     'deleteAll');
    });
});
