<?php

use App\Http\Controllers\Frontend\AppointmentBookingController;
use App\Http\Controllers\Frontend\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::controller(SiteController::class)->group(function(){
    Route::get('/','index')->name('index');
    Route::get('find-parlour','findParlour')->name('find.parlour');
    Route::get('about','about')->name('about');
    Route::get('service','service')->name('service');
    Route::get('blog','blog')->name('blog');
    Route::get('contact','contact')->name('contact');
    Route::post("contact-request",'contactRequest')->name("contact.request");
    Route::get('blog-details/{slug}','blogDetails')->name('blog.details');
    Route::get('link/{slug}','link')->name('link');
});

Route::controller(SiteController::class)->name('frontend.')->group(function(){
    Route::get('search/parlour','searchParlour')->name('parlour.search');

    //parlour booking
    Route::controller(AppointmentBookingController::class)->name('make.appointment.')->group(function(){
        Route::get('make-appointment/{slug}','makeAppointment')->name('index');
        Route::post('store','store')->name('store');
        Route::get('preview/{slug}','preview')->name('preview');
    });

    //
});

