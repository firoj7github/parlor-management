<?php

use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\User\ParlourBookingController;
use App\Http\Controllers\Api\V1\User\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix("user")->name("api.user.")->group(function(){
    
    Route::middleware('auth:api')->group(function(){
        Route::controller(ProfileController::class)->prefix('profile')->group(function(){
            Route::get('info','profileInfo');
            Route::post('info/update','profileInfoUpdate');
            Route::post('password/update','profilePasswordUpdate');
        });
        // Logout Route
        Route::post('logout',[ProfileController::class,'logout']);
        Route::get('notification',[SettingController::class,'notification']);

        Route::controller(ParlourBookingController::class)->prefix('parlour-booking')->group(function(){
            Route::post('checkout','checkout');
            Route::get('payment-method','paymentMethod');
            Route::post('confirm','confirm');
        });
    });
    
    Route::controller(ParlourBookingController::class)->prefix('parlour-booking')->name('parlour.booking.')->group(function(){
        //paypal
        Route::get('success/response/{gateway}','success')->name('payment.success');
        Route::get("cancel/response/{gateway}",'cancel')->name('payment.cancel');

        //stripe
        Route::get('stripe/payment/success/{trx}','stripePaymentSuccess')->name('stripe.payment.success');

        //flutterwave
        Route::get('/flutterwave/callback', 'flutterwaveCallback')->name('flutterwave.callback');

        //razor pay
        Route::get('razor/callback', 'razorCallback')->name('razor.callback');
    });
    
});


