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
}
