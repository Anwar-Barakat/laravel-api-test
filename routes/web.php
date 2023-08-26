<?php

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\RoutePath;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get(RoutePath::for('password.reset', '/reset-password/{token}'), function ($token) {
    return view('auth.password-reset', ['token' => $token]);
})
    ->middleware(['guest:' . config('fortify.guard')])
    ->name('password.reset');

if (App::environment('local')) {
    Route::get('/playground', function () {
        return (new WelcomeMail(User::factory()->make()))->render();
    });
}