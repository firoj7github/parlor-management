<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParlourBooking;
use Illuminate\Http\Request;

class MyBookingController extends Controller
{
    /**
     * Method for view the users history page
     */
    public function index(){
        $page_title     = "| My Booking";
        $data           = ParlourBooking::auth()->with(['parlour','schedule','payment_gateway','user'])
                            ->whereNot('status',global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)->orderBYDESC('id')
                            ->paginate(10);

        return view('user.sections.my-booking.index',compact(
            'page_title',
            'data',
        ));
    }
    /**
     * Method for showing the history details
     * @param $slug
     * @param \Illuminate\Http\Request $request
     */
    public function details(Request $request,$slug){
        $page_title     = "| Booking Details";
        $data           = ParlourBooking::auth()->with(['parlour','schedule','payment_gateway'])->where('slug',$slug)->first();
        return view('user.sections.my-booking.details',compact(
            'page_title',
            'data',
        ));
    }
}
