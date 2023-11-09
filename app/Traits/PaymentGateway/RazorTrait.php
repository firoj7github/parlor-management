<?php

namespace App\Traits\PaymentGateway;

use Exception;
use App\Models\TemporaryData;
use App\Http\Helpers\Response;
use App\Models\ParlourBooking;
use App\Models\UserNotification;
use App\Models\Admin\ParlourList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use App\Notifications\paymentNotification;
use App\Models\Admin\ParlourListHasSchedule;
use Illuminate\Support\Facades\Notification;
use App\Providers\Admin\BasicSettingsProvider;

trait RazorTrait
{
    public function razorInit($output = null) {
        if(!$output) $output = $this->output;
        $credentials = $this->getCredentials($output);
        $api_key = $credentials->public_key;
        $api_secret = $credentials->secret_key;
        $amount = $output['amount']->total_payable_amount ? number_format($output['amount']->total_payable_amount,2,'.','') : 0;
        if(auth()->guard(get_auth_guard())->check()){
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname.' '.$user->lastname ?? '';
        }
        $return_url = route('parlour.booking.razor.callback');
        $payment_link = "https://api.razorpay.com/v1/payment_links";

        // Enter the details of the payment
        $data = array(
            "amount" => $amount * 100,
            "currency" => $output['amount']->sender_cur_code,
            "accept_partial" => false,
            "first_min_partial_amount" => 100,
            "reference_id" =>getTrxNum(),
            "description" => "Payment For Parlour Booking",
            "customer" => array(
                "name" => $user_name ,
                "contact" => $user_phone,
                "email" =>  $user_email
            ),
            "notify" => array(
                "sms" => true,
                "email" => true
            ),
            "reminder_enable" => true,
            "notes" => array(
                "policy_name"=> "eSalon"
            ),
            "callback_url"=> $return_url,
            "callback_method"=> "get"
        );

        $payment_data_string = json_encode($data);
        $payment_ch = curl_init($payment_link);
        curl_setopt($payment_ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($payment_ch, CURLOPT_POSTFIELDS, $payment_data_string);
        curl_setopt($payment_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($payment_ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($api_key . ':' . $api_secret)
        ));

        $payment_response = curl_exec($payment_ch);
        $payment_data = json_decode($payment_response, true);
        if(isset($payment_data['error'])){
            throw new Exception($payment_data['error']['description']);
        }
        $this->razorJunkInsert($data);
        $payment_url = $payment_data['short_url'];
        return redirect($payment_url);
    }
     // Get Flutter wave credentials
     public function getCredentials($output) {
        $gateway = $output['gateway'] ?? null;
        if(!$gateway) throw new Exception("Payment gateway not available");

        $public_key_sample = ['api key','api_key','client id','primary key', 'public key'];
        $secret_key_sample = ['client_secret','client secret','secret','secret key','secret id'];

        $public_key = '';
        $outer_break = false;

        foreach($public_key_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->flutterwavePlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->flutterwavePlainText($label);
                if($label == $modify_item) {
                    $public_key = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        $secret_key = '';
        $outer_break = false;
        foreach($secret_key_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->flutterwavePlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->flutterwavePlainText($label);

                if($label == $modify_item) {
                    $secret_key = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        $encryption_key = '';
        $outer_break = false;

        return (object) [
            'public_key'     => $public_key,
            'secret_key'     => $secret_key,
        ];

    }
    public function razorJunkInsert($response) {
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
            'type'          => PaymentGatewayConst::RAZORPAY,
            'identifier'    => $response['reference_id'],
            'data'          => $data,
        ]);
    }
    public function razorpaySuccess($output = null) {
        if(!$output) $output = $this->output;
        $token = $this->output['tempData']['identifier'] ?? "";
        if(empty($token)) throw new Exception('Transaction Failed. Record didn\'t saved properly. Please try again.');
        return $this->createTransactionRazor($output);
    }
    public function createTransactionRazor($output) {
        $basic_setting = BasicSettingsProvider::get();
        $user = auth()->user();
        $trx_id = generateTrxString('parlour_bookings', 'trx_id', 'PB', 8);
        $inserted_id = $this->insertRecordRazor($output,$trx_id);

        $this->removeTempDataRazor($output);
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

        if( $basic_setting->email_notification == true){
            Notification::route("mail",$user->email)->notify(new paymentNotification($user,$output,$trx_id));
        }
        return true;
    }

    public function insertRecordRazor($output,$trx_id) {
        $trx_id = $trx_id;
        $token = $this->output['tempData']['identifier'] ?? "";
        $user_data = ParlourBooking::where('slug',$output['tempData']['data']->user_record->slug ?? "")->first();
        $this->output['user_data']  = $user_data;
        DB::beginTransaction();
        try{
            if(Auth::guard(get_auth_guard())->check()){
                $user_id = auth()->guard(get_auth_guard())->user()->id;
            }
            // parlour booking

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
        $this->output['trx_id'] = $trx_id;
        return $id;
    }


    public function removeTempDataRazor($output) {
        TemporaryData::where("identifier",$output['tempData']['identifier'])->delete();
    }


    // ********* For API **********
    public function razorInitApi($output = null) {

        if(!$output) $output = $this->output;

        $credentials = $this->getCredentials($output);
        $api_key = $credentials->public_key;
        $api_secret = $credentials->secret_key;
        $amount = $output['amount']->total_payable_amount ? number_format($output['amount']->total_payable_amount,2,'.','') : 0;
        if(auth()->guard(get_auth_guard())->check()){
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname.' '.$user->lastname ?? '';
        }

        $return_url = route('api.user.parlour.booking.razor.callback', "r-source=".PaymentGatewayConst::APP);


        $payment_link = "https://api.razorpay.com/v1/payment_links";

      
        $data = array(
            "amount" => $amount * 100,
            "currency" => $output['amount']->sender_cur_code,
            "accept_partial" => false,
            "first_min_partial_amount" => 100,
            "reference_id" =>getTrxNum(),
            "description" => "Payment For Parlour Booking",
            "customer" => array(
                "name" => $user_name ,
                "contact" => $user_phone,
                "email" =>  $user_email
            ),
            "notify" => array(
                "sms" => true,
                "email" => true
            ),
            "reminder_enable" => true,
            "notes" => array(
                "policy_name"=> "Parlour Booking "
            ),
            "callback_url"=> $return_url,
            "callback_method"=> "get"
        );

        $payment_data_string = json_encode($data);
        $payment_ch = curl_init($payment_link);

        curl_setopt($payment_ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($payment_ch, CURLOPT_POSTFIELDS, $payment_data_string);
        curl_setopt($payment_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($payment_ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($api_key . ':' . $api_secret)
        ));

        $payment_response = curl_exec($payment_ch);
        $payment_data = json_decode($payment_response, true);
        if(isset($payment_data['error'])){
            $message = ['error' => [$payment_data['error']['description']]];
            Response::error($message);
        }

        $this->razorJunkInsert($payment_data);
        $data['short_url'] = $payment_data['short_url'];

        return $data;
    }
}
