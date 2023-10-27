<?php

use App\Http\Controllers\Api\V1\SettingController;
use Illuminate\Support\Facades\Route;

Route::controller(SettingController::class)->group(function(){
    Route::get("basic-settings","basicSettings");
});