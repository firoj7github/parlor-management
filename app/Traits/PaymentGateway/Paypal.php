<?php

namespace App\Traits\PaymentGateway;

use Exception;
use App\Traits\Transaction;
use Illuminate\Support\Str;
use App\Models\TemporaryData;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\ParlourList;
use App\Models\Admin\ParlourListHasSchedule;
use App\Models\ParlourBooking;
use App\Notifications\paypalNotification;
use Illuminate\Support\Facades\Notification;
use App\Providers\Admin\BasicSettingsProvider;
use Srmklive\PayPal\Services\PayPal as PayPalClient;


trait Paypal
{
    public function paypalInit($output = null) {
        if(!$output) $output = $this->output;
        $credentials = $this->getPaypalCredetials($output);
        $config = $this->paypalConfig($credentials,$output['amount']);
        $paypalProvider = new PayPalClient;
        $paypalProvider->setApiCredentials($config);
        $paypalProvider->getAccessToken();
        
        $response = $paypalProvider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('parlour.booking.payment.success',PaymentGatewayConst::PAYPAL),
                "cancel_url" => route('parlour.booking.payment.cancel',PaymentGatewayConst::PAYPAL),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => $output['amount']->sender_cur_code ?? '',
                        "value" => $output['amount']->total_payable_amount ? number_format($output['amount']->total_payable_amount,2,'.','') : 0,
                    ]
                ]
            ]
        ]);
        if(isset($response['id']) && $response['id'] != "" && isset($response['status']) && $response['status'] == "CREATED" && isset($response['links']) && is_array($response['links'])) {
            foreach($response['links'] as $item) {
                if($item['rel'] == "approve") {
                    $this->paypalJunkInsert($response);
                    return redirect()->away($item['href']);
                    break;
                }
            }
        }
        if(isset($response['error']) && is_array($response['error'])) {
            throw new Exception($response['error']['message']);
        }
        throw new Exception("Something went worng! Please try again.");
    }
    // PayPal API Init
    public function paypalInitApi($output = null) {
        if(!$output) $output = $this->output;
        $credentials = $this->getPaypalCredetials($output);
        
        $config = $this->paypalConfig($credentials,$output['amount']);
        $paypalProvider = new PayPalClient;
        $paypalProvider->setApiCredentials($config);
        $paypalProvider->getAccessToken();

        $response = $paypalProvider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" =>route('api.user.send-remittance.payment.success',PaymentGatewayConst::PAYPAL."?r-source=".PaymentGatewayConst::APP),
                "cancel_url" =>route('api.user.send-remittance.payment.cancel',PaymentGatewayConst::PAYPAL."?r-source=".PaymentGatewayConst::APP),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => $output['amount']->sender_cur_code ?? '',
                        "value" => $output['amount']->total_payable_amount ? number_format($output['amount']->total_payable_amount,2,'.','') : 0,
                    ]
                ]
            ]
        ]);

        
        if(isset($response['id']) && $response['id'] != "" && isset($response['status']) && $response['status'] == "CREATED" && isset($response['links']) && is_array($response['links'])) {
            foreach($response['links'] as $item) {
                if($item['rel'] == "approve") {
                    $this->paypalJunkInsert($response);
                    return $response;
                    break;
                }
            }
        }

        if(isset($response['error']) && is_array($response['error'])) {
            throw new Exception($response['error']['message']);
        }

        throw new Exception("Something went worng! Please try again.");
    }

    // Paypal Credential
    public function getPaypalCredetials($output) {
        $gateway = $output['gateway'] ?? null;
        if(!$gateway) throw new Exception("Payment gateway not available");
        $client_id_sample = ['api key','api_key','client id','primary key'];
        $client_secret_sample = ['client_secret','client secret','secret','secret key','secret id'];

        $client_id = '';
        $outer_break = false;
        foreach($client_id_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->paypalPlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->paypalPlainText($label);

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
            $modify_item = $this->paypalPlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->paypalPlainText($label);

                if($label == $modify_item) {
                    $secret_id = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        return (object) [
            'client_id'     => $client_id,
            'client_secret' => $secret_id,
            'mode'          => "sandbox",
        ];

    }

    public function paypalPlainText($string) {
        $string = Str::lower($string);
        return preg_replace("/[^A-Za-z0-9]/","",$string);
    }


    public static function paypalConfig($credentials, $amount_info)
    {
        $config = [
            'mode'    => $credentials->mode ?? 'sandbox',
            'sandbox' => [
                'client_id'         => $credentials->client_id ?? "",
                'client_secret'     => $credentials->client_secret ?? "",
                'app_id'            => "APP-80W284485P519543T",
            ],
            'live' => [
                'client_id'         => $credentials->client_id ?? "",
                'client_secret'     => $credentials->client_secret ?? "",
                'app_id'            => "",
            ],
            'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
            'currency'       => $amount_info->sender_cur_code ?? "",
            'notify_url'     => "", // Change this accordingly for your application.
            'locale'         => 'en_US', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
            'validate_ssl'   => true, // Validate SSL when creating api client.
        ];
        return $config;
    }

    public function paypalJunkInsert($response) {
   
        $output = $this->output;
        $data = [
        
            'gateway'           => $output['gateway']->id,
            'currency'          => $output['currency']->id,
            'amount'            => json_decode(json_encode($output['amount']),true),
            'response'          => $response,
            'creator_table'     => auth()->guard(get_auth_guard())->user()->getTable(),
            'creator_id'        => auth()->guard(get_auth_guard())->user()->id,
            'creator_guard'     => get_auth_guard(),
            'user_record'       => $output['request_data']['data'],
            'payment_method'       => $output['request_data']['payment_method'],
        ];

        
        return TemporaryData::create([
            'user_id'       => Auth::id(),
            'type'          => PaymentGatewayConst::PAYPAL,
            'identifier'    => $response['id'],
            'data'          => $data,
        ]);
    }

    public function paypalSuccess($output = null) {
        if(!$output) $output = $this->output;
        $token = $this->output['tempData']['identifier'] ?? "";
        $user_data = ParlourBooking::where('slug',$output['tempData']['data']->user_record->slug ?? "")->first();
        $this->output['user_data']  = $user_data;

        $credentials = $this->getPaypalCredetials($output);
        $config = $this->paypalConfig($credentials,$output['amount']);
        $paypalProvider = new PayPalClient;
        $paypalProvider->setApiCredentials($config);
        $paypalProvider->getAccessToken();
        $response = $paypalProvider->capturePaymentOrder($token);

        if(isset($response['status']) && $response['status'] == 'COMPLETED') {
            return $this->paypalPaymentCaptured($response,$output);
        }else {
            throw new Exception('Transaction faild. Payment captured faild.');
        }

        if(empty($token)) throw new Exception('Transaction faild. Record didn\'t saved properly. Please try again.');
    }

    public function paypalPaymentCaptured($response,$output) {
        
        // payment successfully captured record saved to database
        $output['capture'] = $response;
        try{
            $trx_id = generateTrxString('parlour_bookings', 'trx_id', 'PB', 8);
            $basic_settings = BasicSettingsProvider::get();
            $user = auth()->user();
            
            // Notification::route("mail",$user->email)->notify(new paypalNotification($user,$output,$trx_id));
            
            
            $parlour_data   = ParlourList::where('id',$output['tempData']['data']->user_record->parlour_id)->first();
            $schedule_data  = ParlourListHasSchedule::where('id',$output['tempData']['data']->user_record->schedule_id)->first();
            UserNotification::create([
                'user_id'  => $output['tempData']['data']->user_record->user_id,
                'message'  => "Your Booking (Parlour: ".$parlour_data->name.",
                Date: ".$output['tempData']['data']->user_record->date.", Time: ".$schedule_data->from_time."-".$schedule_data->to_time.", Serial Number: ".$output['tempData']['data']->user_record->serial_number.") Successfully Booked.", 
            ]);
            
            $this->createTransaction($output, $trx_id);

        }catch(Exception $e) {
            
            throw new Exception($e->getMessage());
        }

        return true;
    }

    public function createTransaction($output, $trx_id) {
        
        $trx_id =  $trx_id;
        $inserted_id = $this->insertRecord($output, $trx_id);
        $this->removeTempData($output);
        if($this->requestIsApiUser()) {
            // logout user
            $api_user_login_guard = $this->output['api_login_guard'] ?? null;
            if($api_user_login_guard != null) {
                auth()->guard($api_user_login_guard)->logout();
            }
        }

    }

    public function insertRecord($output, $trx_id) {
        $trx_id =  $trx_id;
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
            dd($e->getMessage());
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        return $id;
    }

    public function removeTempData($output) {
        $token = $output['capture']['id'];
        TemporaryData::where("identifier",$token)->delete();
    }
}
