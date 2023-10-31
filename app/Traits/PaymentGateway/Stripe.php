<?php

namespace App\Traits\PaymentGateway;

use Exception;
use App\Traits\Transaction;
use Illuminate\Support\Str;
use App\Models\TemporaryData;
use App\Http\Helpers\Response;
use App\Models\ParlourBooking;
use Illuminate\Support\Carbon;
use App\Models\UserNotification;
use App\Models\Admin\ParlourList;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\BasicSettings;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use App\Notifications\sendNotification;
use App\Models\Admin\ParlourListHasSchedule;
use Illuminate\Support\Facades\Notification;
use App\Providers\Admin\BasicSettingsProvider;

trait Stripe
{
    public function stripeInit($output = null) {

        $basic_settings = BasicSettingsProvider::get();
        if(!$output) $output = $this->output;
        
        $credentials = $this->getStripeCredentials($output);
        $reference = generateTransactionReference();
        $amount = $output['amount']->total_payable_amount ? number_format($output['amount']->total_payable_amount,2,'.','') : 0;
        $currency = $output['currency']['currency_code']??"USD";

        if(auth()->guard(get_auth_guard())->check()){
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname.' '.$user->lastname ?? '';
        }
        $return_url = route('parlour.booking.stripe.payment.success', $reference);
        
        

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card',
            'amount'          => $amount,
            'email'           => $user_email,
            'tx_ref'          => $reference,
            'currency'        =>  $currency,
            'redirect_url'    => $return_url,
            'customer'        => [
                'email'        => $user_email,
                "phone_number" => $user_phone,
                "name"         => $user_name
            ],
            "customizations" => [
                "title"       => "Parlour Booking",
                "description" => dateFormat('d M Y', Carbon::now()),
            ]
        ];
        
       //start stripe pay link
       $stripe = new \Stripe\StripeClient($credentials->secret_key);

       //create product for Product Id
       try{
            $product_id = $stripe->products->create([
                'name' => 'Parlour Booking( '.$basic_settings->site_name.' )',
            ]);
       }catch(Exception $e){
            throw new Exception($e->getMessage());
       }
       
       //create price for Price Id
       try{
            $price_id =$stripe->prices->create([
                'currency' =>  $currency,
                'unit_amount' => $amount*100,
                'product' => $product_id->id??""
              ]);
       }catch(Exception $e){
            throw new Exception("Something Is Wrong, Please Contact With Owner");
       }
       //create payment live links
        try{
            $payment_link = $stripe->paymentLinks->create([
                'line_items' => [
                [
                    'price' => $price_id->id,
                    'quantity' => 1,
                ],
                ],
                'after_completion' => [
                'type' => 'redirect',
                'redirect' => ['url' => $return_url],
                ],


            ]);
        }catch(Exception $e){
            throw new Exception("Something Is Wrong, Please Contact With Owner");
        }
        $this->stripeJunkInsert($data);
        
        return redirect($payment_link->url."?prefilled_email=".@$user->email);

    }

    public function getStripeCredentials($output) {
        $gateway = $output['gateway'] ?? null;
        if(!$gateway) throw new Exception("Payment gateway not available");
        $client_id_sample = ['publishable_key','publishable key','publishable-key'];
        $client_secret_sample = ['secret id','secret-id','secret_id'];

        $client_id = '';
        $outer_break = false;
        foreach($client_id_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->stripePlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->stripePlainText($label);

                if($label == $modify_item) {
                    $client_id = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }


        $secret_id = '';
        $outer_break = false;
        foreach($client_secret_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->stripePlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->stripePlainText($label);

                if($label == $modify_item) {
                    $secret_id = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        return (object) [
            'publish_key'     => $client_id,
            'secret_key' => $secret_id,

        ];

    }

    public function stripePlainText($string) {
        $string = Str::lower($string);
        return preg_replace("/[^A-Za-z0-9]/","",$string);
    }

    public function stripeJunkInsert($response) {
        $output = $this->output;
        $user = auth()->guard(get_auth_guard())->user();

        $creator_table = auth()->guard(get_auth_guard())->user()->getTable();
        $creator_id = auth()->guard(get_auth_guard())->user()->id;

        $data = [
            'gateway'   => $output['gateway']->id,
            'currency'  => $output['currency']->id,
            'amount'    => json_decode(json_encode($output['amount']),true),
            'response'  => $response,
            'user_record' => $output['request_data']['data'],
            'payment_method'       => $output['request_data']['payment_method'],
            'creator_table' => $creator_table,
            'creator_id'    => $creator_id,
            'creator_guard' => get_auth_guard(),
        ];
        
        return TemporaryData::create([
            'user_id'       => Auth::user()->id,
            'type'          => PaymentGatewayConst::STRIPE,
            'identifier'    => $response['tx_ref'],
            'data'          => $data,

        ]);
    }
    public function stripeSuccess($output = null) {
        if(!$output) $output = $this->output;
        
        $token = $this->output['tempData']['identifier'] ?? "";
        if(empty($token)) throw new Exception('Transaction failed. Record didn\'t saved properly. Please try again.');
        
        return $this->createTransactionStripe($output);
        
    }

    public function createTransactionStripe($output) {
        $basic_setting = BasicSettings::first();
        $user = auth()->user();
        $trx_id = generateTrxString('parlour_bookings', 'trx_id', 'PB', 8);
        
        $inserted_id = $this->insertRecordStripe($output,$trx_id);
        $this->removeTempDataStripe($output);

        if($this->requestIsApiUser()) {
            // logout user
            $api_user_login_guard = $this->output['api_login_guard'] ?? null;
            if($api_user_login_guard != null) {
                auth()->guard($api_user_login_guard)->logout();
            }
        }
        if(auth()->check()){
            $parlour_data   = ParlourList::where('id',$output['tempData']['data']->user_record->parlour_id)->first();
            $schedule_data  = ParlourListHasSchedule::where('id',$output['tempData']['data']->user_record->schedule_id)->first();
            UserNotification::create([
                'user_id'  => $output['tempData']['data']->user_record->user_id,
                'message'  => "Your Booking (Parlour: ".$parlour_data->name.",
                Date: ".$output['tempData']['data']->user_record->date.", Time: ".$schedule_data->from_time."-".$schedule_data->to_time.", Serial Number: ".$output['tempData']['data']->user_record->serial_number.") Successfully Booked.", 
            ]);
        }
        if( $basic_setting->email_notification == true){
            Notification::route("mail",$user->email)->notify(new sendNotification($user,$output,$trx_id));
        }
    }

    public function insertRecordStripe($output, $trx_id) {
       
        $trx_id = $trx_id;
        $token = $this->output['tempData']['identifier'] ?? "";
        
        $user_data = ParlourBooking::where('slug',$output['tempData']['data']->user_record->slug ?? "")->first();
        $this->output['user_data']  = $user_data;

 
        DB::beginTransaction();
        
            try{
                $id = DB::table("parlour_bookings")->insertGetId([
                    'parlour_id'                    => $this->output['user_data']->parlour_id,
                    'schedule_id'                   => $this->output['user_data']->schedule_id,
                    'user_id'                       => $this->output['user_data']->user_id,
                    'payment_gateway_currency_id'   => $output['currency']->id,
                    'trx_id'                        => $trx_id,
                    'date'                          => $this->output['user_data']->date,
                    'payment_method'                => $output['gateway']->name,
                    'slug'                          => $this->output['user_data']->slug,
                    'total_charge'                  => $output['amount']->total_charge,
                    'price'                         => $output['amount']->price,
                    'payable_price'                 => $output['amount']->payable_amount,
                    'gateway_payable_price'         => $output['amount']->total_payable_amount,
                    'service'                       => json_encode($this->output['user_data']->service),
                    'message'                       => $this->output['user_data']->message,
                    'remark'                        => $output['gateway']->name,
                    'serial_number'                 => $this->output['user_data']->serial_number,
                    'status'                        => global_const()::PARLOUR_BOOKING_STATUS_CONFIRM_PAYMENT,
                    'created_at'                    => now(),
                ]);
                $previous_data = ParlourBooking::where('slug',$this->output['user_data']->slug)->first();
                $previous_data->delete();
                DB::commit();
            }catch(Exception $e) {
                DB::rollBack();
                throw new Exception($e->getMessage());
            }
        
        return $id;
    }

    public function removeTempDataStripe($output) {
        $token = session()->get('identifier');
        TemporaryData::where("identifier",$token)->delete();
    }
    // for api
    public function stripeInitApi($output = null) {
        $basic_settings = BasicSettingsProvider::get();
        if(!$output) $output = $this->output;
        $credentials = $this->getStripeCredentials($output);
        $reference = generateTransactionReference();
        $amount = $output['amount']->total_amount ? number_format($output['amount']->total_amount,2,'.','') : 0;
        $currency = $output['currency']['currency_code']??"USD";

        if(auth()->guard(get_auth_guard())->check()){
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname.' '.$user->lastname ?? '';
        }

        $return_url = route('api.user.send-remittance.stripe.payment.success', $reference."?r-source=".PaymentGatewayConst::APP);


         // Enter the details of the payment
         $data = [
            'payment_options' => 'card',
            'amount'          => $amount,
            'email'           => $user_email,
            'tx_ref'          => $reference,
            'currency'        =>  $currency,
            'redirect_url'    => $return_url,
            'customer'        => [
                'email'        => $user_email,
                "phone_number" => $user_phone,
                "name"         => $user_name
            ],
            "customizations" => [
                "title"       => "Add Money",
                "description" => dateFormat('d M Y', Carbon::now()),
            ]
        ];

       //start stripe pay link
       $stripe = new \Stripe\StripeClient($credentials->secret_key);

       //create product for Product Id
       try{
            $product_id = $stripe->products->create([
                'name' => 'Send Remittance( '.$basic_settings->site_name.' )',
            ]);
       }catch(Exception $e){
            $error = ['error'=>[$e->getMessage()]];
            return Response::error($error);
       }
       //create price for Price Id
       try{
            $price_id =$stripe->prices->create([
                'currency' =>  $currency,
                'unit_amount' => $amount*100,
                'product' => $product_id->id??""
              ]);
       }catch(Exception $e){
            $error = ['error'=>["Something Is Wrong, Please Contact With Owner"]];
            return Response::error($error);
       }
       //create payment live links
       try{
            $payment_link = $stripe->paymentLinks->create([
                'line_items' => [
                [
                    'price' => $price_id->id,
                    'quantity' => 1,
                ],
                ],
                'after_completion' => [
                'type' => 'redirect',
                'redirect' => ['url' => $return_url],
                ],
            ]);
        }catch(Exception $e){
            $error = ['error'=>["Something Is Wrong, Please Contact With Owner"]];
            return Response::error($error);
        }
        $data['link'] =  $payment_link->url;
        $data['trx'] =  $reference;

        $this->stripeJunkInsert($data);
        return $data;
    }

    

}
