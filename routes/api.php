<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthenticate\AppAuthenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], static function() {
    Route::post('/login', [AuthController::class, 'processLogin']);
    Route::post('/reset_password', [AuthController::class, 'processResetPassword']);
    Route::post('/verify_reset_password', [AuthController::class, 'verifyResetPassword']);
    Route::post('/update_password', [AuthController::class, 'updatePassword']);
    Route::post('/register', [AuthController::class, 'processRegister']);
    Route::post('/verify_register', [AuthController::class, 'verifyRegister']);
    Route::post('/verify_new_location', [AuthController::class, 'verifyNewLocation']);
    Route::group(['middleware' => [StartSession::class]], static function () {
        Route::get('/twitter/redirect', [SocialLoginController::class, 'redirect']);
        Route::get('/twitter/callback', [SocialLoginController::class, 'callback']);
    });
    Route::get('/{social}/redirect', [SocialLoginController::class, 'redirect']);
    Route::get('/{social}/callback', [SocialLoginController::class, 'callback']);
});

Route::group(['middleware' => AppAuthenticate::class], static function () {
    Route::get('/test', function () {
        $a = services()->userService()->query()->get();
        dd($a);
    });
});


Route::get('/', [UserController::class, 'index']);
Route::post('/', [UserController::class, 'store']);
Route::get('/{id}', [UserController::class, 'show']);
Route::delete('/{id}', [UserController::class, 'destroy']);
Route::put('/{id}', [UserController::class, 'update']);
