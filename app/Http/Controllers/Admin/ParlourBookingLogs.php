<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParlourBooking;
use Illuminate\Http\Request;

class ParlourBookingLogs extends Controller
{
    /**
     * Method for show all booking logs
     */
    public function index(){
        $page_title     = "All Logs";
        $data           = ParlourBooking::with(['parlour','schedule','payment_gateway'])->whereNot('status',global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)->orderBYDESC('id')->paginate(15);

        return view('admin.sections.booking-logs.index',compact(
            'page_title',
            'data',
        ));
    }
    /**
     * Method for booking log details
     * @param $trx_id
     * @param \Illuminate\Http\Request $request
     */
    public function details(Request $request,$trx_id){
        $page_title     = "Booking Details";
        $data           = ParlourBooking::with(['parlour','schedule','payment_gateway'])->where('trx_id',$trx_id)->first();
        if(!$data) return back()->with(['error' => ['Data Not Found!']]);

        return view('admin.sections.booking-logs.details',compact(
            'page_title',
            'data',
        ));
    }
}
