<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\SettingController;

Route::controller(SettingController::class)->group(function(){
    Route::get("basic-settings","basicSettings");
    Route::get("parlour-list","parlourList");
    Route::get("schedule-service","scheduleService");
    Route::post("search-parlour","searchParlour");
    Route::get("country-list","countryList");
});
