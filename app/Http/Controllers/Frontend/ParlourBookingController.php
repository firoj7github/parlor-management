<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Models\ParlourBooking;
use App\Models\UserNotification;
use App\Models\Admin\ParlourList;
use App\Models\Admin\UsefullLink;
use App\Models\Admin\SiteSections;
use App\Constants\SiteSectionConst;
use App\Http\Controllers\Controller;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\TransactionSetting;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\ParlourListHasSchedule;
use App\Models\Admin\PaymentGatewayCurrency;
use App\Http\Helpers\PaymentGateway as PaymentGatewayHelper;

class ParlourBookingController extends Controller
{
    /**
     * Method for show parlour booking page
     * @param $slug
     * @param \Illuminate\Http\Request $request
     */
    public function getService(Request $request,$slug){
        $page_title         = "| Parlour Booking";
        $parlour            = ParlourList::with(['schedules','services'])->where('slug',$slug)->first();
        $validated_user     = auth()->user();
        $footer_slug        = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer             = SiteSections::getData($footer_slug)->first();
        $usefull_links      = UsefullLink::where('status',true)->get();
        $contact_slug       = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact            = SiteSections::getData($contact_slug)->first();
        

        return view('frontend.pages.parlour-booking.index',compact(
            'page_title',
            'parlour',
            'validated_user',
            'footer',
            'usefull_links',
            'contact'
        ));
    }
    /**
     * Method for store appointment booking information and passed it to preview page
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request){
        $validated_user         = auth()->user();
        if(!$validated_user) return back()->with(['error' => ['Please Login First.']]);
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
            return back()->withErrors($validator)->withInput($request->all());
        }
        $validated                  = $validator->validate();
        $validated['slug']          = Str::uuid();
        $slug                       = $validated['parlour'];
        $parlour                    = ParlourList::where('slug',$slug)->first();
        if(!$parlour) return back()->with(['error'=> ['Parlour Not Found!']]);
        if(auth()->check()){
            $validated['user_id']   = auth()->user()->id;
        }
        else{
            $validated['user_id']   = null;
        }

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


        $alrady_appointed = ParlourBooking::where('parlour_id',$parlour->id)->where('schedule_id',$validated['schedule_id'])->count();

        if($alrady_appointed >= $schedule->max_client) {
            return back()->with(['error' => ['Appiontment Limit is over!']]);
        }

        $next_appointment_no = $alrady_appointed + 1;
        $validated['serial_number'] = $next_appointment_no;

        try{
            $booking = ParlourBooking::create($validated);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return redirect()->route('parlour.booking.preview',$booking->slug);
    }
    /**
     * Method for show the preview page 
     * @param $slug
     * @param \Illuminate\Http\Request $request
     */
    public function preview(Request $request,$slug){
        $page_title         = "| Appointment Preview";
        $booking            = ParlourBooking::with(['parlour','schedule','payment_gateway'])->where('slug',$slug)->first();
        $payment_gateway   = PaymentGatewayCurrency::whereHas('gateway', function ($gateway) {
            $gateway->where('slug', PaymentGatewayConst::payment_method_slug());
            $gateway->where('status', 1);
        })->get();
        $footer_slug        = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer             = SiteSections::getData($footer_slug)->first();
        $usefull_links      = UsefullLink::where('status',true)->get();
        $contact_slug       = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact            = SiteSections::getData($contact_slug)->first();

        return view('frontend.pages.parlour-booking.preview',compact(
            'page_title',
            'booking',
            'payment_gateway',
            'footer',
            'usefull_links',
            'contact'
        )); 
    }
    /**
     * Method for confirm the booking
     * @param $slug
     * @param \Illuminate\Http\Request $request
     */
    public function confirm(Request $request,$slug){

        $data       = ParlourBooking::with(['payment_gateway','parlour','schedule','user'])->where('slug',$slug)->first();
        $validator  = Validator::make($request->all(),[
            'payment_method'    => 'required',
        ]);
        $validated  = $validator->validate();
        $from_time  = $data->schedule->from_time ?? '';

        $to_time    = $data->schedule->to_time ?? '';
        if($validated['payment_method'] == global_const()::CASH_PAYMENT){
            try{
                $trx_id = generateTrxString('parlour_bookings', 'trx_id', 'PB', 8);
                $data->update([
                    'trx_id'            => $trx_id,
                    'payment_method'    => $validated['payment_method'],
                    'remark'            => 'CASH',
                    'status'            => true,
                ]);
                UserNotification::create([
                    'user_id'  => auth()->user()->id,
                    'message'  => "Your Booking (Parlour: ".$data->parlour->name.",
                    Date: ".$data->date.", Time: ".$from_time."-".$to_time.", Serial Number: ".$data->serial_number.") Successfully Booked.", 
                ]);
            }catch(Exception $e){

                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return redirect()->route('user.history.index')->with(['success' => ['Congratulations! Parlour Booking Confirmed Successfully.']]);
        }else{
            $requested_data         = [
                'data'              => $data,
                'payment_method'    => $validated['payment_method']
            ];
            
            try{
                $instance = PaymentGatewayHelper::init($requested_data)->gateway()->render();
            }catch(Exception $e){
                return back()->with(['error' => [$e->getMessage()]]);
            }
            return $instance;
        }
    }
    /**
     * Method for paypal payment succes
     */
    public function success(Request $request, $gateway){
        $requestData = $request->all();
        $token = $requestData['token'] ?? "";
        $checkTempData = TemporaryData::where("type",$gateway)->where("identifier",$token)->first();
        if(!$checkTempData) return redirect()->route('find.parlour')->with(['error' => ['Transaction faild. Record didn\'t saved properly. Please try again.']]);
        $checkTempData = $checkTempData->toArray();
        try{
            
            $transaction = PaymentGatewayHelper::init($checkTempData)->responseReceive();
        }catch(Exception $e) {
            dd($e->getMessage());
            return back()->with(['error' => [$e->getMessage()]]);
        }
        
        return redirect()->route("user.history.index")->with(['success' => ['Congratulations! Parlour Booking Confirmed Successfully.']]);
    }
}
