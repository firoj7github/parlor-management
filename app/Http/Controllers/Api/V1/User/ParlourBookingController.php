<?php

namespace App\Http\Controllers\Api\V1\User;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Http\Helpers\Response;
use App\Models\ParlourBooking;
use App\Models\UserNotification;
use App\Models\Admin\ParlourList;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentGateway;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\TransactionSetting;
use App\Notifications\emailNotification;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\ParlourListHasSchedule;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Support\Facades\Notification;
use KingFlamez\Rave\Facades\Rave as Flutterwave;
use App\Http\Helpers\PaymentGateway as PaymentGatewayHelper;

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
    /**
     * Method for get payment method
     */
    public function paymentMethod(){
        $payment_gateway   = PaymentGatewayCurrency::whereHas('gateway', function ($gateway) {
            $gateway->where('slug', PaymentGatewayConst::payment_method_slug());
            $gateway->where('status', 1);
        })->get();
        return Response::success(['Payment Method Data Fetch Successfully.'],$payment_gateway,200);
    }
    /**
     * Method for confirm booking
     */
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
                return Response::error(['Something went wrong! Please try again.'],[],404);
            }
            return Response::success(['Congratulations! Parlour Booking Confirmed Successfully.'],$data,200);
        }
        if($validated['payment_method'] != global_const()::CASH_PAYMENT){
            $requested_data         = [
                'data'              => $data,
                'payment_method'    => $validated['payment_method']
            ];
            
            $payment_gateways_currencies = PaymentGatewayCurrency::where('id',$validated['payment_method'])->whereHas('gateway', function ($gateway) {
                $gateway->where('slug', PaymentGatewayConst::payment_method_slug());
                $gateway->where('status', 1);
            })->first();
            if(!$payment_gateways_currencies){
                $error = ['error'=>['Gateway Information is not available. Please provide payment gateway currency alias']];
                return Response::error($error);
            }
            $user = auth()->user();
            try{
               
                $instance = PaymentGatewayHelper::init($requested_data)->gateway()->api()->get();
                $trx = $instance['response']['id']??$instance['response']['trx']??$instance['response']['reference_id']??$instance['response'];;
                $temData = TemporaryData::where('identifier',$trx)->first();
                if(!$temData){
                    $error = ['error'=>["Invalid Request"]];
                    return Response::error($error);
                }
                $payment_gateway_currency = PaymentGatewayCurrency::where('id', $temData->data->currency)->first();
                $payment_gateway = PaymentGateway::where('id', $temData->data->gateway)->first();
                if($payment_gateway->type == "AUTOMATIC") {
                    if($temData->type == PaymentGatewayConst::STRIPE) {
                        $payment_informations =[
                            'trx'                       =>  $temData->identifier,
                            'gateway_currency_name'     =>  $payment_gateway_currency->name,
                            'sender_cur_code'           => $temData->data->amount->sender_cur_code,
                            'sender_cur_rate'           => $temData->data->amount->sender_cur_rate,
                            'price'                     => $temData->data->amount->price,
                            'total_charge'              => $temData->data->amount->total_charge,
                            'payable_amount'            => $temData->data->amount->payable_amount,
                            'total_payable_amount'      => $temData->data->amount->total_payable_amount,
                            'exchange_rate'             => $temData->data->amount->exchange_rate,
                            'default_currency'          => $temData->data->amount->default_currency,
                        ];
                        $data =[
                             'gategay_type'          => $payment_gateway->type,
                             'gateway_currency_name' => $payment_gateway_currency->name,
                             'alias'                 => $payment_gateway_currency->alias,
                             'identify'              => $temData->type,
                             'payment_informations'  => $payment_informations,
                             'url'                   => @$temData->data->response->link."?prefilled_email=".@$user->email,
                             'method'                => "get",
                        ];
     
                        return Response::success(['Parlour Booking Added'], $data);
                    }elseif($temData->type == PaymentGatewayConst::RAZORPAY) {
                     $payment_informations =[
                        'trx'                       =>  $temData->identifier,
                        'gateway_currency_name'     =>  $payment_gateway_currency->name,
                        'sender_cur_code'           => $temData->data->amount->sender_cur_code,
                        'sender_cur_rate'           => $temData->data->amount->sender_cur_rate,
                        'price'                     => $temData->data->amount->price,
                        'total_charge'              => $temData->data->amount->total_charge,
                        'payable_amount'            => $temData->data->amount->payable_amount,
                        'total_payable_amount'      => $temData->data->amount->total_payable_amount,
                        'exchange_rate'             => $temData->data->amount->exchange_rate,
                        'default_currency'          => $temData->data->amount->default_currency,
                     ];
                     $data =[
                          'gategay_type'          => $payment_gateway->type,
                          'gateway_currency_name' => $payment_gateway_currency->name,
                          'alias'                 => $payment_gateway_currency->alias,
                          'identify'              => $temData->type,
                          'payment_informations'  => $payment_informations,
                          'url' => @$instance['response']['short_url'],
                          'method' => "get",
                     ];
     
                     return Response::success(['Parlour Booking Added'], $data);
                }else if($temData->type == PaymentGatewayConst::PAYPAL) {
                    
                        $payment_informations = [
                            'trx'                       => $temData->identifier,
                            'gateway_currency_name'     => $payment_gateway_currency->name,
                            'sender_cur_code'           => $temData->data->amount->sender_cur_code,
                            'sender_cur_rate'           => $temData->data->amount->sender_cur_rate,
                            'price'                     => $temData->data->amount->price,
                            'total_charge'              => $temData->data->amount->total_charge,
                            'payable_amount'            => $temData->data->amount->payable_amount,
                            'total_payable_amount'      => $temData->data->amount->total_payable_amount,
                            'exchange_rate'             => $temData->data->amount->exchange_rate,
                            'default_currency'          => $temData->data->amount->default_currency,
                        ];
                        $data =[
                            'gategay_type'          => $payment_gateway->type,
                            'gateway_currency_name' => $payment_gateway_currency->name,
                            'alias'                 => $payment_gateway_currency->alias,
                            'identify'              => $temData->type,
                            'payment_informations'  => $payment_informations,
                            'url'                   => @$temData->data->response->links,
                            'method'                => "get",
                        ];
                        
                        return Response::success(['Parlour Booking Added'], $data);
     
                    }else if($temData->type == PaymentGatewayConst::FLUTTER_WAVE) {
                        $payment_informations =[
                            'trx'                       => $temData->identifier,
                            'gateway_currency_name'     => $payment_gateway_currency->name,
                            'sender_cur_code'           => $temData->data->amount->sender_cur_code,
                            'sender_cur_rate'           => $temData->data->amount->sender_cur_rate,
                            'price'                     => $temData->data->amount->price,
                            'total_charge'              => $temData->data->amount->total_charge,
                            'payable_amount'            => $temData->data->amount->payable_amount,
                            'total_payable_amount'      => $temData->data->amount->total_payable_amount,
                            'exchange_rate'             => $temData->data->amount->exchange_rate,
                            'default_currency'          => $temData->data->amount->default_currency,
                        ];
                        $data =[
                            'gateway_type'          => $payment_gateway->type,
                            'gateway_currency_name' => $payment_gateway_currency->name,
                            'alias'                 => $payment_gateway_currency->alias,
                            'identify'              => $temData->type,
                            'payment_informations'  => $payment_informations,
                            'url'                   => @$temData->data->response->link,
                            'method'                => "get",
                        ];
     
                        return Response::success(['Parlour Booking Added'], $data);
                     }elseif($temData->type == PaymentGatewayConst::SSLCOMMERZ) {
     
                        $payment_informations =[
                        'trx'                       =>  $temData->identifier,
                        'gateway_currency_name'     =>  $payment_gateway_currency->name,
                        'sender_cur_code'           => $temData->data->amount->sender_cur_code,
                        'sender_cur_rate'           => $temData->data->amount->sender_cur_rate,
                        'price'                     => $temData->data->amount->price,
                        'total_charge'              => $temData->data->amount->total_charge,
                        'payable_amount'            => $temData->data->amount->payable_amount,
                        'total_payable_amount'      => $temData->data->amount->total_payable_amount,
                        'exchange_rate'             => $temData->data->amount->exchange_rate,
                        'default_currency'          => $temData->data->amount->default_currency,
                        ];
                        $data =[
                        'gateway_type' => $payment_gateway->type,
                        'gateway_currency_name' => $payment_gateway_currency->name,
                        'alias' => $payment_gateway_currency->alias,
                        'identify' => $temData->type,
                        'payment_informations' => $payment_informations,
                        'url' => $instance['response']['link'],
                        'method' => "get",
                        ];
     
                        return Response::success(['Parlour Booking Added'],$data);
                     }
                }else{
                    $error = ['error'=>["Something is wrong"]];
                    return Response::error($error);
                }
     
            }catch(Exception $e) {
                $error = ['error'=>[$e->getMessage()]];
                return Response::error($error);
            }
            
        }
    }
    /**
     * Method for paypal payment succes
     */
    public function success(Request $request, $gateway){
        
        $requestData = $request->all();
        $token = $requestData['token'] ?? "";
        $checkTempData = TemporaryData::where("type",$gateway)->where("identifier",$token)->first();
        if(!$checkTempData) {
            return Response::error(["Transaction failed. Record didn\'t saved properly. Please try again."],[],400);
        }
        $checkTempData = $checkTempData->toArray();
        try{
            PaymentGatewayHelper::init($checkTempData)->responseReceiveApi();
        }catch(Exception $e) {
            $message = ['error' => [$e->getMessage()]];
            return Response::error($message);
        }
        
        return Response::success(["Congratulations! Parlour Booking Confirmed Successfully."],[],200);
    }
    /**
     * Method for paypal method cancel
     */
    public function cancel(Request $request, $gateway) {
        $requestData = $request->all();
        $token = $requestData['token'] ?? "";
        if( $token){
            TemporaryData::where("identifier",$token)->delete();
        }
        return Response::success(["Cancel Payment"],[],200);
    }
    /**
     * Method for stripe payment success
     */
    public function stripePaymentSuccess($trx){
        $token = $trx;
        $checkTempData = TemporaryData::where("type",PaymentGatewayConst::STRIPE)->where("identifier",$token)->first();
        if(!$checkTempData) {
            return Response::error(["Transaction failed. Record didn\'t saved properly. Please try again."],[],400);
        }
        $checkTempData = $checkTempData->toArray();

        try{
            PaymentGatewayHelper::init($checkTempData)->responseReceiveApi();
        }catch(Exception $e) {
            $message = ['error' => [$e->getMessage()]];
            return Response::error($message);
        }
        return Response::success(['success' => ['Congratulations! Parlour Booking Confirmed Successfully.']]);
    }
    /**
     * Method for flutterwave success
     */
    public function flutterwaveCallback(){

        $status = request()->status;
        //if payment is successful
        if ($status ==  'successful') {

            $transactionID = Flutterwave::getTransactionIDFromCallback();
            $data = Flutterwave::verifyTransaction($transactionID);

            $requestData = request()->tx_ref;
            $token = $requestData;

            $checkTempData = TemporaryData::where("type",'flutterwave')->where("identifier",$token)->first();
            $message = ['error' => ['Transaction faild. Record didn\'t saved properly. Please try again.']];
            if(!$checkTempData) return Response::error($message);


            $checkTempData = $checkTempData->toArray();

            try{
               PaymentGatewayHelper::init($checkTempData)->responseReceiveApi();
            }catch(Exception $e) {
                $message = ['error' => [$e->getMessage()]];
                Response::error($message);
            }
            return Response::success(['success' => ['Congratulations! Parlour Booking Confirmed Successfully.']]);

        }
        elseif ($status ==  'cancelled'){
            return Response::error(['Payment Cancelled']);
        }
        else{
            return Response::error(['Payment Failed']);
        }
    }
}
