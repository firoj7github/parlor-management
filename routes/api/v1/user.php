<?php

use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\User\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix("user")->name("api.user.")->group(function(){
    Route::controller(ProfileController::class)->prefix('profile')->group(function(){
        Route::get('info','profileInfo');
        Route::post('info/update','profileInfoUpdate');
        Route::post('password/update','profilePasswordUpdate');
    });
    // Logout Route
    Route::post('logout',[ProfileController::class,'logout']);
    Route::get('notification',[SettingController::class,'notification']);
    
});

