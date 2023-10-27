<?php

use App\Http\Controllers\Api\V1\User\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\User\Auth\LoginController;
use App\Http\Controllers\Api\V1\User\Auth\RegisterController;
use App\Http\Controllers\Api\V1\User\AuthorizationController;
use Illuminate\Support\Facades\Route;

// User Auth Routes
Route::controller(RegisterController::class)->group(function() {
    Route::post("register","register");
});

Route::controller(LoginController::class)->group(function(){
    Route::post("login","login");
});

// Forget password routes
Route::controller(ForgotPasswordController::class)->prefix("password/forgot")->group(function(){
    Route::post('find/user','findUserSendCode');
    Route::post('verify/code','verifyCode');
    Route::get('resend/code','resendCode');
    Route::post('reset','resetPassword');
});

Route::controller(AuthorizationController::class)->prefix("user")->middleware(['auth:api'])->group(function(){
    Route::post("verify/code","verifyCode");
    Route::get("resend/code","resendCode");
});


