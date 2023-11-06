<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Helpers\Response;
use App\Models\ParlourBooking;
use App\Http\Controllers\Controller;

class MyBookingController extends Controller
{
    /**
     * Method for get my-booking data
     */
    public function index(){
        $data       = ParlourBooking::with(['parlour','schedule'])
                        ->where('user_id',auth()->user()->id)
                        ->whereNot('status',global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)
                        ->get();
        return Response::success(['Your Booking Data.'],$data,200);
    }
}
