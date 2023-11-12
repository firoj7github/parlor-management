<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\ParlourBooking;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;
use App\Notifications\emailNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class ParlourBookingLogs extends Controller
{
    /**
     * Method for show all booking logs
     */
    public function index(){
        $page_title     = "All Logs";
        $data           = ParlourBooking::with(['parlour','schedule','payment_gateway'])
                            ->whereNot('status',global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)
                            ->orderByDesc('id')
                            ->paginate(15);

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
    /**
     * Method for update Status for Booking Logs
     * @param $trx_id
     * @param \Illuminate\Http\Request $request
     */
    public function statusUpdate(Request $request,$trx_id){
        $data           = ParlourBooking::with(['parlour','schedule','payment_gateway'])->where('trx_id',$trx_id)->first();
        if(!$data) return back()->with(['error' =>  ['Data Not Found!']]);
        $validator      = Validator::make($request->all(),[
            'status'    => 'required|integer',
        ]);

        if($validator->fails()){
            return Response::error(['error' => $validator->errors()]);
        }
        $validated = $validator->validate();
        $basic_setting = BasicSettings::first();
        try{
            $data->update([
                'status'    => $validated['status'],
            ]);
            $user   = User::where('id',$data->user_id)->first();
            if($basic_setting->email_notification == true){
                Notification::route("mail",$user->email)->notify(new emailNotification($user,$data,$data->trx_id));
            }
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success'  => ['Booking Status Updated Successfully.']]);

    }
    /**
     * Method for show Pending log page 
     * @param string
     * @return view
     */
    public function pending(){
        $page_title     = "Pending Logs";
        $data           = ParlourBooking::with(['parlour','schedule','payment_gateway'])->where('status',global_const()::PARLOUR_BOOKING_STATUS_PENDING)->get();

        return view('admin.sections.booking-logs.pending',compact(
            'page_title',
            'data',
        ));
    }
    /**
     * Method for show progress log page 
     * @param string
     * @return view
     */
    public function confirmPayment(){
        $page_title     = "Confirm Payment Logs";
        $data           = ParlourBooking::with(['parlour','schedule','payment_gateway'])->where('status',global_const()::PARLOUR_BOOKING_STATUS_CONFIRM_PAYMENT)->get();

        return view('admin.sections.booking-logs.confirm-payment',compact(
            'page_title',
            'data',
        ));
    }
    /**
     * Method for show hold log page 
     * @param string
     * @return view
     */
    public function hold(){
        $page_title    = "Hold Logs";
        $data          = ParlourBooking::with(['parlour','schedule','payment_gateway'])->where('status',global_const()::PARLOUR_BOOKING_STATUS_HOLD)->get();

        return view('admin.sections.booking-logs.hold',compact(
            'page_title',
            'data',
        ));
    }
    /**
     * Method for show settle log page 
     * @param string
     * @return view
     */
    public function settled(){
        $page_title     = "Settled Logs";
        $data           = ParlourBooking::with(['parlour','schedule','payment_gateway'])->where('status',global_const()::PARLOUR_BOOKING_STATUS_SETTLED)->get();

        return view('admin.sections.booking-logs.settled',compact(
            'page_title',
            'data',
        ));
    }
    /**
     * Method for show Complete log page 
     * @param string
     * @return view
     */
    public function complete(){
        $page_title     = "Complete Logs";
        $data           = ParlourBooking::with(['parlour','schedule','payment_gateway'])->where('status',global_const()::PARLOUR_BOOKING_STATUS_COMPLETE)->get();

        return view('admin.sections.booking-logs.complete',compact(
            'page_title',
            'data',
        ));
    }
    /**
     * Method for show canceled log page 
     * @param string
     * @return view
     */
    public function canceled(){
        $page_title    = "Canceled Logs";
        $data          = ParlourBooking::with(['parlour','schedule','payment_gateway'])->where('status',global_const()::PARLOUR_BOOKING_STATUS_CANCEL)->get();

        return view('admin.sections.booking-logs.cancel',compact(
            'page_title',
            'data',
        ));
    }
    /**
     * Method for show failed log page 
     * @param string
     * @return view
     */
    public function failed(){
        $page_title     = "Failed Logs";
        $data           = ParlourBooking::with(['parlour','schedule','payment_gateway'])->where('status',global_const()::PARLOUR_BOOKING_STATUS_FAILED)->get();

        return view('admin.sections.booking-logs.failed',compact(
            'page_title',
            'data',
        ));
    }
    /**
     * Method for show refunded page 
     * @param string
     * @return view
     */
    public function refunded(){
        $page_title     = "Refunded Logs";
        $data           = ParlourBooking::with(['parlour','schedule','payment_gateway'])->where('status',global_const()::PARLOUR_BOOKING_STATUS_REFUND)->get();

        return view('admin.sections.booking-logs.refunded',compact(
            'page_title',
            'data',
        ));
    }
    /**
     * Method for show delayed log page 
     * @param string
     * @return view
     */
    public function delayed(){
        $page_title     = "Delayed Logs";
        $data           = ParlourBooking::with(['parlour','schedule','payment_gateway'])->where('status',global_const()::PARLOUR_BOOKING_STATUS_DELAYED)->get();

        return view('admin.sections.booking-logs.delayed',compact(
            'page_title',
            'data',
        ));
    }
    /**
     * Method for search currency item
     * @param $string
     */
    public function search(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);

        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        $data = ParlourBooking::search($validated['text'])->select()->limit(10)->get();
        return view('admin.components.search.parlour-booking-search',compact(
            'data',
        ));
    }
}
