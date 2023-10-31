<?php

namespace App\Traits\PaymentGateway;

use Exception;
use App\Traits\Transaction;
use Illuminate\Support\Str;
use App\Models\TemporaryData;
use App\Models\ParlourBooking;
use Illuminate\Support\Carbon;
use App\Models\UserNotification;
use App\Models\Admin\ParlourList;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\BasicSettings;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Models\Admin\ParlourListHasSchedule;
use Illuminate\Support\Facades\Notification;
use App\Notifications\flutterwaveNotification;
use App\Notifications\paymentNotification;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

trait FlutterwaveTrait
{

    public function flutterwaveInit($output = null) {
        if(!$output) $output = $this->output;
        
        $credentials = $this->getFlutterCredentials($output);

        $this->flutterwaveSetSecreteKey($credentials);
        //This generates a payment reference
        $reference = Flutterwave::generateReference();
        

        $amount = $output['amount']->total_payable_amount ? number_format($output['amount']->total_payable_amount,2,'.','') : 0;

        if(auth()->guard(get_auth_guard())->check()){
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname.' '.$user->lastname ?? '';
        }
        $return_url = route('parlour.booking.flutterwave.callback');

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount'          => $amount,
            'email'           => $user_email,
            'tx_ref'          => $reference,
            'currency'        => $output['currency']['currency_code']??"NGN",
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

        $payment = Flutterwave::initializePayment($data);
        if( $payment['status'] == "error"){
            throw new Exception($payment['message']);
        };

        $this->flutterWaveJunkInsert($data);

        if ($payment['status'] !== 'success') {
            return;
        }

        return redirect($payment['data']['link']);
    }


    public function flutterWaveJunkInsert($response) {
        $output = $this->output;
        $user = auth()->guard(get_auth_guard())->user();
      
        $creator_table = auth()->guard(get_auth_guard())->user()->getTable();
        $creator_id = auth()->guard(get_auth_guard())->user()->id;
        
        $data = [
            'gateway'      => $output['gateway']->id,
            'currency'     => $output['currency']->id,
            'amount'       => json_decode(json_encode($output['amount']),true),
            'response'     => $response,
            
            'creator_table' => $creator_table,
            'creator_id'    => $creator_id,
            'creator_guard' => get_auth_guard(),
            'user_record'       => $output['request_data']['data'],
            'payment_method'    => $output['request_data']['payment_method'],
        ];

        return TemporaryData::create([
            'user_id'    => Auth::id(),
            'type'       => PaymentGatewayConst::FLUTTER_WAVE,
            'identifier' => $response['tx_ref'],
            'data'       => $data,
        ]);
    }



    // Get Flutter wave credentials
    public function getFlutterCredentials($output) {
        
        $gateway = $output['gateway'] ?? null;
        if(!$gateway) throw new Exception("Payment gateway not available");

        $public_key_sample = ['api key','api_key','client id','primary key', 'public key'];
        $secret_key_sample = ['client_secret','client secret','secret','secret key','secret id'];
        $encryption_key_sample = ['encryption_key','encryption secret','secret hash', 'encryption id'];

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
        foreach($encryption_key_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->flutterwavePlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->flutterwavePlainText($label);

                if($label == $modify_item) {
                    $encryption_key = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        return (object) [
            'public_key'     => $public_key,
            'secret_key'     => $secret_key,
            'encryption_key' => $encryption_key,
        ];

    }

    public function flutterwavePlainText($string) {
        $string = Str::lower($string);
        return preg_replace("/[^A-Za-z0-9]/","",$string);
    }

    public function flutterwaveSetSecreteKey($credentials){
        Config::set('flutterwave.secretKey',$credentials->secret_key);
        Config::set('flutterwave.publicKey',$credentials->public_key);
        Config::set('flutterwave.secretHash',$credentials->encryption_key);
    }

    public function flutterwaveSuccess($output = null) {
        if(!$output) $output = $this->output;
        
        $token = $this->output['tempData']['identifier'] ?? "";
       
        $trx_id = generateTrxString('parlour_bookings', 'trx_id', 'PB', 8);
        
        $user = auth()->user();
            
        $parlour_data   = ParlourList::where('id',$output['tempData']['data']->user_record->parlour_id)->first();
        $schedule_data  = ParlourListHasSchedule::where('id',$output['tempData']['data']->user_record->schedule_id)->first();
        UserNotification::create([
            'user_id'  => $output['tempData']['data']->user_record->user_id,
            'message'  => "Your Booking (Parlour: ".$parlour_data->name.",
            Date: ".$output['tempData']['data']->user_record->date.", Time: ".$schedule_data->from_time."-".$schedule_data->to_time.", Serial Number: ".$output['tempData']['data']->user_record->serial_number.") Successfully Booked.", 
        ]);
            
        
        if(empty($token)) throw new Exception('Transaction faild. Record didn\'t saved properly. Please try again.');
        return $this->createTransactionFlutterwave($output,$trx_id);
    }

    public function createTransactionFlutterwave($output,$trx_id) {
        $trx_id =  $trx_id;
        $user = auth()->user();
        $basic_setting = BasicSettings::first();
        $inserted_id = $this->insertRecordFlutterwave($output,$trx_id);
        if( $basic_setting->email_notification == true){
            Notification::route("mail",$user->email)->notify(new paymentNotification($user,$output,$trx_id));
        }
        $this->removeTempDataFlutterWave($output);

        if($this->requestIsApiUser()) {
            // logout user
            $api_user_login_guard = $this->output['api_login_guard'] ?? null;
            if($api_user_login_guard != null) {
                auth()->guard($api_user_login_guard)->logout();
            }
        }

        return true;

    }
    public function insertRecordFlutterwave($output,$trx_id) {
        
        $token = $this->output['tempData']['identifier'] ?? "";
        $trx_id =  $trx_id;
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
                DB::commit();
            }catch(Exception $e) {
                DB::rollBack();
                throw new Exception($e->getMessage());
            }
        
        return $id;
    }

    public function removeTempDataFlutterWave($output) {
        TemporaryData::where("identifier",$output['tempData']['identifier'])->delete();
    }


    // ********* For API **********
    public function flutterwaveInitApi($output = null) {
        if(!$output) $output = $this->output;
        $credentials = $this->getFlutterCredentials($output);
        $this->flutterwaveSetSecreteKey($credentials);

        //This generates a payment reference
        $reference = Flutterwave::generateReference();

        $amount = $output['amount']->total_amount ? number_format($output['amount']->total_amount,2,'.','') : 0;

        if(auth()->guard(get_auth_guard())->check()){
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname.' '.$user->lastname ?? '';
        }

        $return_url = route('api.user.send-remittance.flutterwave.callback', "r-source=".PaymentGatewayConst::APP);

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount'          => $amount,
            'email'           => $user_email,
            'tx_ref'          => $reference,
            'currency'        => $output['currency']['currency_code']??"NGN",
            'redirect_url'    => $return_url,
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

        $payment = Flutterwave::initializePayment($data);
        $data['link'] = $payment['data']['link'];
        $data['trx'] = $data['tx_ref'];

        $this->flutterWaveJunkInsert($data);

        if ($payment['status'] !== 'success') {
            // notify something went wrong
            return;
        }

        return $data;
        
    }

}
