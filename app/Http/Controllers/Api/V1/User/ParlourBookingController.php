<?php

namespace App\Http\Controllers\Api\V1\User;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\ParlourBooking;
use App\Models\UserNotification;
use App\Models\Admin\ParlourList;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;
use App\Models\Admin\TransactionSetting;
use App\Notifications\emailNotification;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\ParlourListHasSchedule;
use Illuminate\Support\Facades\Notification;

class ParlourBookingController extends Controller
{
    /**
     * Method for parlour booking checkout
     */
    public function checkout(Request $request){
        if(auth()->check() == false) return Response::error(['Please Login First!'],404);
        $charge_data            = TransactionSetting::where('slug','parlour')->where('status',1)->first();
        $validator              = Validator::make($request->all(),[
            'parlour'           => 'required',
            'price'             => 'required',
            'service'           => "required|array",
            'service.*'         => "required|string|max:255",
            'date'              => "required",
            'schedule'          => 'required',
            'message'           => "nullable"
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $validated                  = $validator->validate();
        
        $validated['slug']          = Str::uuid();
        $slug                       = $validated['parlour'];
        $parlour                    = ParlourList::where('slug',$slug)->first();
        
        if(!$parlour) return Response::error(['Parlour Not Found.'],[],404);
        
        $validated['user_id']   = auth()->user()->id;
        

        $validated['parlour_id']   = $parlour->id;
        
        $schedule = ParlourListHasSchedule::where('id',$validated['schedule'])->whereHas('parlour',function($q) use ($parlour) {
            $q->where('id',$parlour->id);
        })->first();

        if(!$schedule) {
            return Response::error(['Schedule Not Found.'],[],404);
        }
        
        $validated['schedule_id']   = $validated['schedule'];

        $price                      = floatval($validated['price']); 
        $fixed_charge               = floatval($charge_data->fixed_charge);
        $percent_charge             = floatval($charge_data->percent_charge);
        $total_percent_charge       = ($percent_charge / 100) * $price;
        $total_charge               = $fixed_charge + $total_percent_charge;
        $total_price                = $price + $total_charge;
        $validated['total_charge']  = $total_charge;
        $validated['price']         = $price;
        $validated['payable_price'] = $total_price;
        $validated['status']        = global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT;

        $alrady_appointed = ParlourBooking::where('parlour_id',$parlour->id)->where('schedule_id',$validated['schedule_id'])->count();
        
        if($alrady_appointed >= $schedule->max_client) {
            return Response::error(['Booking limit is over.'],[],404);
        }

        $next_appointment_no = $alrady_appointed + 1;
        $validated['serial_number'] = $next_appointment_no;
        
        try{
            $booking    = ParlourBooking::create($validated);
        }catch(Exception $e){
            return Response::error(['Something went wrong! Please try again.'],[],404);
        }
        return Response::success(['Parlour booking successfully'],$booking,200);
    }
    public function confirm(Request $request){
        $data       = ParlourBooking::with(['payment_gateway','parlour','schedule','user'])->where('slug',$request->slug)->first();
        $otp_exp_sec = $data->booking_exp_seconds ?? global_const()::BOOKING_EXP_SEC;
        
        if($data->created_at->addSeconds($otp_exp_sec) < now()) {
            $data->delete();
            return redirect()->route('find.parlour')->with(['error' => ['Booking Time Out!']]);
        }
        
        $validator  = Validator::make($request->all(),[
            'payment_method'    => 'required',
        ]);
        $validated  = $validator->validate();
        $from_time  = $data->schedule->from_time ?? '';

        $to_time    = $data->schedule->to_time ?? '';
        $user       = auth()->user();
        $basic_setting = BasicSettings::first();
        if($validated['payment_method'] == global_const()::CASH_PAYMENT){
            
            try{
                $trx_id = generateTrxString('parlour_bookings', 'trx_id', 'PB', 8);
                $data->update([
                    'trx_id'            => $trx_id,
                    'payment_method'    => $validated['payment_method'],
                    'remark'            => 'CASH',
                    'status'            => global_const()::PARLOUR_BOOKING_STATUS_PENDING,
                ]);
                UserNotification::create([
                    'user_id'  => auth()->user()->id,
                    'message'  => "Your Booking (Parlour: ".$data->parlour->name.",
                    Date: ".$data->date.", Time: ".$from_time."-".$to_time.", Serial Number: ".$data->serial_number.") Successfully Booked.", 
                ]);
                if($basic_setting->email_notification == true){
                    Notification::route("mail",$user->email)->notify(new emailNotification($user,$data,$trx_id));
                }
            }catch(Exception $e){
                return Response::error(['Something went wrong! Please try again.'],[],404);;
            }
            return Response::success(['Congratulations! Parlour Booking Confirmed Successfully.'],$data,200);
        }
    }
}
