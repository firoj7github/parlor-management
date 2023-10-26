<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ParlourBooking;
use App\Models\Admin\ParlourList;
use App\Models\Admin\ServiceType;
use App\Models\Admin\UsefullLink;
use App\Models\Admin\SiteSections;
use App\Constants\SiteSectionConst;
use App\Http\Controllers\Controller;
use App\Constants\PaymentGatewayConst;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\ParlourListHasSchedule;
use App\Models\Admin\PaymentGatewayCurrency;
use App\Http\Helpers\PaymentGateway as PaymentGatewayHelper;

class AppointmentBookingController extends Controller
{
    /**
     * Method for show parlour booking page
     * @param $slug
     * @param \Illuminate\Http\Request $request
     */
    public function makeAppointment(Request $request,$slug){
        $page_title         = "| Parlour Booking";
        $parlour            = ParlourList::with(['schedules'])->where('slug',$slug)->first();
        $service_types      = ServiceType::where('status',true)->get();
        $payment_gateway   = PaymentGatewayCurrency::whereHas('gateway', function ($gateway) {
            $gateway->where('slug', PaymentGatewayConst::payment_method_slug());
            $gateway->where('status', 1);
        })->get();
        $validated_user     = auth()->user();
        $footer_slug        = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer             = SiteSections::getData($footer_slug)->first();
        $usefull_links      = UsefullLink::where('status',true)->get();
        $contact_slug       = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact            = SiteSections::getData($contact_slug)->first();
        

        return view('frontend.pages.parlour-booking.index',compact(
            'page_title',
            'parlour',
            'service_types',
            'payment_gateway',
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
        $validator              = Validator::make($request->all(),[
            'parlour'           => 'required',
            'schedule'          => 'required',
            'price'             => 'required',
            'payment_gateway'   => 'required',
            'name'              => 'required',
            'mobile'            => 'nullable',
            'email'             => 'required',
            'gender'            => 'required',
            'type'              => "nullable|array",
            'type.*'            => "nullable|string|max:255",
            'message'           => "nullable"
        ]);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput($request->all());
        }
        $validated                  = $validator->validate();
        $validated['slug']          = Str::uuid();
        $slug                       = $validated['parlour'];
        $payment_gateway            = $validated['payment_gateway'];
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
        $validated['schedule_id'] = $validated['schedule'];
        $payment_method     = PaymentGatewayCurrency::where('id',$payment_gateway)->first();
        if(!$payment_method) return back()->with(['error' => ['Payment Gateway Not Found!']]);
        
        $validated['payment_gateway_currency_id']   = $payment_method->id;
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
        return redirect()->route('frontend.make.appointment.preview',$booking->slug);
    }
    /**
     * Method for show the preview page 
     * @param $slug
     * @param \Illuminate\Http\Request $request
     */
    public function preview(Request $request,$slug){
        $page_title         = "Appointment Preview";
        $booking            = ParlourBooking::with(['parlour','schedule','payment_gateway'])->where('slug',$slug)->first();
        $footer_slug        = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer             = SiteSections::getData($footer_slug)->first();
        $usefull_links      = UsefullLink::where('status',true)->get();
        $contact_slug       = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact            = SiteSections::getData($contact_slug)->first();

        return view('frontend.pages.parlour-booking.preview',compact(
            'page_title',
            'booking',
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
        $data       = ParlourBooking::with(['payment_gateway'])->where('slug',$slug)->first();
        try{
            $instance = PaymentGatewayHelper::init($data)->gateway()->render();
        }catch(Exception $e){
            return back()->with(['error' => [$e->getMessage()]]);
        }
        return $instance;
    }
}
