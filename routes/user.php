<?php

use App\Http\Controllers\Frontend\ParlourBookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\HistoryController;
use App\Http\Controllers\User\MyBookingController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SupportTicketController;

Route::prefix("user")->name("user.")->group(function(){
    Route::controller(DashboardController::class)->group(function(){
        Route::get('index','index')->name('dashboard');
        Route::post('logout','logout')->name('logout');
        Route::DELETE('delete/account','deleteAccount')->name('delete.account');
    });

    Route::controller(ProfileController::class)->prefix("profile")->name("profile.")->group(function(){
        Route::get('/','index')->name('index');
        Route::put('password/update','passwordUpdate')->name('password.update');
        Route::put('update','update')->name('update');
    });

    Route::controller(SupportTicketController::class)->prefix("support-ticket")->name("support.ticket.")->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('conversation/{encrypt_id}','conversation')->name('conversation');
        Route::post('message/send','messageSend')->name('message.send');
    });

    Route::controller(MyBookingController::class)->prefix("my-booking")->name("my.booking.")->group(function(){
        Route::get('index','index')->name('index');
        Route::get('details/{slug}','details')->name('details');
    });
});
