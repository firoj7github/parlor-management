<?php

namespace App\Http\Controllers\Api\V1\User;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\ParlourBooking;
use App\Models\Admin\ParlourList;
use App\Http\Controllers\Controller;
use App\Models\Admin\TransactionSetting;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\ParlourListHasSchedule;

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
        dd($request->all());
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        
        $validated                  = $validator->validate();
        $validated['slug']          = Str::uuid();
        $slug                       = $validated['parlour'];
        $parlour                    = ParlourList::where('slug',$slug)->first();
        if(!$parlour) return back()->with(['error'=> ['Parlour Not Found!']]);
       
        $validated['user_id']   = auth()->user()->id;
        

        $validated['parlour_id']   = $parlour->id;

        $schedule = ParlourListHasSchedule::where('id',$validated['schedule'])->whereHas('parlour',function($q) use ($parlour) {
            $q->where('id',$parlour->id);
        })->first();

        if(!$schedule) {
            return back()->with(['error' => ['Schedule Not Found!']]);
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
            return back()->with(['error' => ['Appiontment Limit is over!']]);
        }

        $next_appointment_no = $alrady_appointed + 1;
        $validated['serial_number'] = $next_appointment_no;
        try{
            $booking    = ParlourBooking::create($validated);
        }catch(Exception $e){
            return Response::error(['Something went wrong! Please try again.'],[],404);
        }
        return Response::success(['Appointment booking successfully'],$booking,200);
    }
}
