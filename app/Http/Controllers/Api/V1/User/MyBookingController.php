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
        $parlour_image_path   = [
            'base_url'      => url('/'),
            'path_location' => files_asset_path_basename('site-section'),
            'default_image' => files_asset_path_basename('default')
        ];
        return Response::success(['Your Booking Data.'],[
            'data' => $data,
            'parlour_image_path'    => $parlour_image_path
        ],200);
    }
}
