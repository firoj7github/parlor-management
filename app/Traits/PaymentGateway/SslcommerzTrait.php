<?php

namespace App\Traits\PaymentGateway;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\TemporaryData;
use App\Models\ParlourBooking;
use App\Models\UserNotification;
use App\Models\Admin\ParlourList;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\BasicSettings;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\ParlourListHasSchedule;
use App\Notifications\paymentNotification;
use Illuminate\Support\Facades\Notification;


trait SslcommerzTrait
{
    public function sslcommerzInit($output = null) {
        if(!$output) $output = $this->output;
        
        $credentials = $this->getSslCredentials($output);
        $reference = generateTransactionReference();
        $amount = $output['amount']->total_payable_amount ? number_format($output['amount']->total_payable_amount,2,'.','') : 0;
        $currency = $output['currency']['currency_code']??"USD";

        if(auth()->guard(get_auth_guard())->check()){
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname.' '.$user->lastname ?? '';
        }

        $post_data = array();
        $post_data['store_id'] = $credentials->store_id??"";
        $post_data['store_passwd'] = $credentials->store_password??"";
        $post_data['total_amount'] =$amount;
        $post_data['currency'] = $currency;
        $post_data['tran_id'] =  $reference;
        $post_data['success_url'] =  route('parlour.booking.ssl.success');
        $post_data['fail_url'] = route('parlour.booking.ssl.fail');
        $post_data['cancel_url'] = route('parlour.booking.ssl.cancel');
       
        # $post_data['multi_card_name'] = "mastercard,visacard,amexcard";  # DISABLE TO DISPLAY ALL AVAILABLE

        # EMI INFO
        $post_data['emi_option'] = "1";
        $post_data['emi_max_inst_option'] = "9";
        $post_data['emi_selected_inst'] = "9";

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $user->fullname??"Test Customer";
        $post_data['cus_email'] = $user->email??"test@test.com";
        $post_data['cus_add1'] = $user->address->country??"Dhaka";
        $post_data['cus_add2'] = $user->address->address??"Dhaka";
        $post_data['cus_city'] = $user->address->city??"Dhaka";
        $post_data['cus_state'] = $user->address->state??"Dhaka";
        $post_data['cus_postcode'] = $user->address->zip??"1000";
        $post_data['cus_country'] = $user->address->country??"Bangladesh";
        $post_data['cus_phone'] = $user->full_mobile??"01711111111";
        $post_data['cus_fax'] = "";



        # PRODUCT INFORMATION
        $post_data['product_name'] = "Parlour Booking";
        $post_data['product_category'] = "Parlour Booking";
        $post_data['product_profile'] = "Parlour Booking";
        # SHIPMENT INFORMATION
        $post_data['shipping_method'] = "NO";

         $data = [
            'request_data'    => $post_data,
            'amount'          => $amount,
            'email'           => $user_email,
            'tx_ref'          => $reference,
            'currency'        =>  $currency,
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

        if( $credentials->mode == Str::lower(PaymentGatewayConst::ENV_SANDBOX)){
            $link_url =  $credentials->sandbox_url;
        }else{
            $link_url =  $credentials->live_url;
        }
        # REQUEST SEND TO SSLCOMMERZ
        $direct_api_url = $link_url."/gwprocess/v4/api.php";

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $direct_api_url );
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1 );
        curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


        $content = curl_exec($handle );
        $result = json_decode( $content,true);
        if( $result['status']  != "SUCCESS"){
            throw new Exception($result['failedreason']);
        }
        $this->sslJunkInsert($data);
        return redirect($result['GatewayPageURL']);

    }

    public function getSslCredentials($output) {
        $gateway = $output['gateway'] ?? null;
        if(!$gateway) throw new Exception("Payment gateway not available");
        $store_id_sample = ['store_id','Store Id','store-id'];
        $store_password_sample = ['Store Password','store-password','store_password'];
        $sandbox_url_sample = ['Sandbox Url','sandbox-url','sandbox_url'];
        $live_url_sample = ['Live Url','live-url','live_url'];

        $store_id = '';
        $outer_break = false;
        foreach($store_id_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->sllPlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->sllPlainText($label);

                if($label == $modify_item) {
                    $store_id = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }


        $store_password = '';
        $outer_break = false;
        foreach($store_password_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->sllPlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->sllPlainText($label);

                if($label == $modify_item) {
                    $store_password = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }
        $sandbox_url = '';
        $outer_break = false;
        foreach($sandbox_url_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->sllPlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->sllPlainText($label);

                if($label == $modify_item) {
                    $sandbox_url = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }
        $live_url = '';
        $outer_break = false;
        foreach($live_url_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->sllPlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->sllPlainText($label);

                if($label == $modify_item) {
                    $live_url = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        $mode = $gateway->env;

        $paypal_register_mode = [
            PaymentGatewayConst::ENV_SANDBOX => "sandbox",
            PaymentGatewayConst::ENV_PRODUCTION => "live",
        ];
        if(array_key_exists($mode,$paypal_register_mode)) {
            $mode = $paypal_register_mode[$mode];
        }else {
            $mode = "sandbox";
        }

        return (object) [
            'store_id'     => $store_id,
            'store_password' => $store_password,
            'sandbox_url' => $sandbox_url,
            'live_url' => $live_url,
            'mode'          => $mode,

        ];

    }

    public function sllPlainText($string) {
        $string = Str::lower($string);
        return preg_replace("/[^A-Za-z0-9]/","",$string);
    }

    public function sslJunkInsert($response) {
        $output = $this->output;
        $user = auth()->guard(get_auth_guard())->user();
        $creator_table = $creator_id = $wallet_table = $wallet_id = null;

        $creator_table = auth()->guard(get_auth_guard())->user()->getTable();
        $creator_id = auth()->guard(get_auth_guard())->user()->id;
       
            $data = [
                'gateway'       => $output['gateway']->id,
                'currency'      => $output['currency']->id,
                'amount'        => json_decode(json_encode($output['amount']),true),
                'response'      => $response,
                'user_record'       => $output['request_data']['data'],
                'payment_method'    => $output['request_data']['payment_method'],
                'creator_table' => $creator_table,
                'creator_id'    => $creator_id,
                'creator_guard' => get_auth_guard(),
            ];

        return TemporaryData::create([
            'user_id'       => Auth::user()->id,
            'type'          => PaymentGatewayConst::SSLCOMMERZ,
            'identifier'    => $response['tx_ref'],
            'data'          => $data,
        ]);
    }

    public function sslcommerzSuccess($output = null) {
        
        if(!$output) $output = $this->output;
        $token = $this->output['tempData']['identifier'] ?? "";
        if(empty($token)) throw new Exception('Transaction failed. Record didn\'t saved properly. Please try again.');
        return $this->createTransactionSsl($output);
    }

    public function createTransactionSsl($output) {
        
        $basic_setting = BasicSettings::first();
        $user = auth()->user();
        $trx_id = generateTrxString('parlour_bookings', 'trx_id', 'PB', 8);
       
        $inserted_id = $this->insertRecordSsl($output,$trx_id);
        if( $basic_setting->email_notification == true){
            Notification::route("mail",$user->email)->notify(new paymentNotification($user,$output,$trx_id));
        }
        $this->removeTempDataSsl($output);
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
                'message'  => [
                    'title' => "Your Booking",
                    'parlour'   => $parlour_data->name,
                    'date'      => $output['tempData']['data']->user_record->date,
                    'from_time' => $schedule_data->from_time,
                    'to_time'   => $schedule_data->to_time,
                    'serial_number' => $output['tempData']['data']->user_record->serial_number,
                    'success'       => "Successfully Booked."
                ],
            ]);
        }
        return true;
    }

    public function insertRecordSsl($output,$trx_id) {
        
        $trx_id = $trx_id;
        $token = $this->output['tempData']['identifier'] ?? "";
        $user_data = ParlourBooking::where('slug',$output['tempData']['data']->user_record->slug ?? "")->first();
        $this->output['user_data']  = $user_data;
        DB::beginTransaction();
        try{
            if(Auth::guard(get_auth_guard())->check()){
                $user_id = auth()->guard(get_auth_guard())->user()->id;
            }
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
            if(auth()->check()){
                $parlour_data   = ParlourList::where('id',$output['tempData']['data']->user_record->parlour_id)->first();
                $schedule_data  = ParlourListHasSchedule::where('id',$output['tempData']['data']->user_record->schedule_id)->first();
                UserNotification::create([
                    'user_id'  => $output['tempData']['data']->user_record->user_id,
                    'message'  => [
                        'title' => "Your Booking",
                        'parlour'   => $parlour_data->name,
                        'date'      => $output['tempData']['data']->user_record->date,
                        'from_time' => $schedule_data->from_time,
                        'to_time'   => $schedule_data->to_time,
                        'serial_number' => $output['tempData']['data']->user_record->serial_number,
                        'success'       => "Successfully Booked."
                    ],
                ]);
            }
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        return $id;
    }

    public function removeTempDataSsl($output) {
        TemporaryData::where("identifier",$output['tempData']['identifier'])->delete();
    }
    //for api
    public function sslcommerzInitApi($output = null) {
        if(!$output) $output = $this->output;
        $credentials = $this->getSslCredentials($output);
        $reference = generateTransactionReference();
        $amount = $output['amount']->total_payable_amount ? number_format($output['amount']->total_payable_amount,2,'.','') : 0;
        $currency = $output['currency']['currency_code']??"USD";

        if(auth()->guard(get_auth_guard())->check()){
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname.' '.$user->lastname ?? '';
        }
        $post_data = array();
        $post_data['store_id'] = $credentials->store_id??"";
        $post_data['store_passwd'] = $credentials->store_password??"";
        $post_data['total_amount'] =$amount;
        $post_data['currency'] = $currency;
        $post_data['tran_id'] =  $reference;
        $post_data['success_url'] =  route('api.parlour.booking.ssl.success',"?r-source=".PaymentGatewayConst::APP);
        $post_data['fail_url'] = route('api.parlour.booking.ssl.fail',"?r-source=".PaymentGatewayConst::APP);
        $post_data['cancel_url'] = route('api.parlour.booking.ssl.cancel',"?r-source=".PaymentGatewayConst::APP);
        # $post_data['multi_card_name'] = "mastercard,visacard,amexcard";  # DISABLE TO DISPLAY ALL AVAILABLE
        
        # EMI INFO
        $post_data['emi_option'] = "1";
        $post_data['emi_max_inst_option'] = "9";
        $post_data['emi_selected_inst'] = "9";

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $user->fullname??"Test Customer";
        $post_data['cus_email'] = $user->email??"test@test.com";
        $post_data['cus_add1'] = $user->address->country??"Dhaka";
        $post_data['cus_add2'] = $user->address->address??"Dhaka";
        $post_data['cus_city'] = $user->address->city??"Dhaka";
        $post_data['cus_state'] = $user->address->state??"Dhaka";
        $post_data['cus_postcode'] = $user->address->zip??"1000";
        $post_data['cus_country'] = $user->address->country??"Bangladesh";
        $post_data['cus_phone'] = $user->full_mobile??"01711111111";
        $post_data['cus_fax'] = "";



        # PRODUCT INFORMATION
        $post_data['product_name'] = "Send Remittance";
        $post_data['product_category'] = "Send Remittance";
        $post_data['product_profile'] = "Send Remittance";
        # SHIPMENT INFORMATION
        $post_data['shipping_method'] = "NO";

         $data = [
            'request_data'    => $post_data,
            'amount'          => $amount,
            'email'           => $user_email,
            'tx_ref'          => $reference,
            'currency'        =>  $currency,
            'customer'        => [
                'email'        => $user_email,
                "phone_number" => $user_phone,
                "name"         => $user_name
            ],
            "customizations" => [
                "title"       => "Send Remittance",
                "description" => dateFormat('d M Y', Carbon::now()),
            ]
        ];

        if( $credentials->mode == Str::lower(PaymentGatewayConst::ENV_SANDBOX)){
            $link_url =  $credentials->sandbox_url;
        }else{
            $link_url =  $credentials->live_url;
        }
        # REQUEST SEND TO SSLCOMMERZ
        $direct_api_url = $link_url."/gwprocess/v4/api.php";

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $direct_api_url );
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1 );
        curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


        $content = curl_exec($handle );
        $result = json_decode( $content,true);
        if( $result['status']  != "SUCCESS"){
            throw new Exception($result['failedreason']);
        }

        $data['link'] = $result['GatewayPageURL'];
        $data['trx'] =  $reference;

        $this->sslJunkInsert($data);
        return $data;

    }

}
