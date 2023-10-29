<?php
namespace App\Http\Helpers;

use Exception;
use Illuminate\Support\Str;
use App\Models\TemporaryData;
use App\Models\Admin\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Traits\PaymentGateway\Paypal;
use App\Traits\PaymentGateway\Stripe;
use App\Traits\PaymentGateway\Manual;
use App\Constants\PaymentGatewayConst;
use Illuminate\Support\Facades\Validator;
use App\Traits\PaymentGateway\RazorTrait;
use App\Models\Admin\PaymentGatewayCurrency;
use App\Models\ParlourBooking;
use Illuminate\Validation\ValidationException;
use App\Traits\PaymentGateway\SslcommerzTrait;
use App\Traits\PaymentGateway\FlutterwaveTrait;

class PaymentGateway {

    

    protected $request_data;
    protected $output;
    private $transaction_id;

    public function __construct(array $request_data){
        $this->request_data = $request_data;
    }

    public static function init(array $data) {
        return new PaymentGateway($data);
    }

    public function gateway() {
        $request_data = $this->request_data;
        if(empty($request_data)) throw new Exception("Gateway Information is not available. Please provide payment gateway currency alias");

        $gateway_currency   = PaymentGatewayCurrency::where("id",$request_data['payment_method'])->first();
        

        if(!$gateway_currency || !$gateway_currency->gateway) {
            throw ValidationException::withMessages([
                $this->$request_data->payment_gateway->alias = "Gateway not available",
            ]);
        }

        if($gateway_currency->gateway->isAutomatic()) {
            $this->output['gateway']    = $gateway_currency->gateway;
            $this->output['currency']   = $gateway_currency;
            $this->output['amount']     = $this->amount();
            $this->output['distribute'] = $this->gatewayDistribute($gateway_currency->gateway);
        }elseif($gateway_currency->gateway->isManual()){
            $this->output['gateway']    = $gateway_currency->gateway;
            $this->output['currency']   = $gateway_currency;
            $this->output['amount']     = $this->amount();
            $this->output['distribute'] = $this->gatewayDistribute($gateway_currency->gateway);
        }

        $this->output['request_data']   = $request_data;
        dd($this->output['request_data']);
        return $this;
    }

    // public function validator($data) {
    //     return Validator::make($data,[
    //         'identifier'                => "nullable",
    //     ]);
    // }

    public function get() {
        return $this->output;
    }

    public function gatewayDistribute($gateway = null) {

        if(!$gateway) $gateway = $this->output['gateway'];
        $alias = Str::lower($gateway->alias);
        if($gateway->type == PaymentGatewayConst::AUTOMATIC){
            $method = PaymentGatewayConst::register($alias);
        }elseif($gateway->type == PaymentGatewayConst::MANUAL){ 
            $method = PaymentGatewayConst::register(strtolower($gateway->type));
        }

        if(method_exists($this,$method)) {
            return $method;
        }
        return throw new Exception("Gateway(".$gateway->name.") Trait or Method (".$method."()) does not exists");
    }

    public function amount() {
        $currency = $this->output['currency'] ?? null;
        if(!$currency) throw new Exception("Gateway currency not found");
        return $this->chargeCalculate($currency);
    }

    public function chargeCalculate($currency,$receiver_currency = null) {
        
        $temporary_data         = TemporaryData::where('identifier',$this->request_data['identifier'])->first();
        $amount                 = ($temporary_data->data->payable_amount / $temporary_data->data->sender_base_rate) * $temporary_data->data->currency->rate;
        $fees                   = $temporary_data->data->fees;
        $convert_amount         = $temporary_data->data->convert_amount;
        $receive_money          = $temporary_data->data->receive_money;
        $sender_currency_rate   = $currency->rate;
        
        ($sender_currency_rate == "" || $sender_currency_rate == null) ? $sender_currency_rate = 0 : $sender_currency_rate;
        ($amount == "" || $amount == null) ? $amount : $amount;

        if($receiver_currency) {
            $receiver_currency_rate = $receiver_currency->rate;
            ($receiver_currency_rate == "" || $receiver_currency_rate == null) ? $receiver_currency_rate = 0 : $receiver_currency_rate;
            $exchange_rate  = ($receiver_currency_rate / $sender_currency_rate);
            $will_get       = $receive_money;

            $data = [
                'requested_amount'          => $amount,
                'sender_cur_code'           => $currency->currency_code,
                'sender_cur_rate'           => $sender_currency_rate ?? 0,
                'receiver_cur_code'         => $receiver_currency->currency_code,
                'receiver_cur_rate'         => $receiver_currency->rate ?? 0,
                'total_charge'              => $fees,
                'total_amount'              => $amount,
                'exchange_rate'             => $exchange_rate,
                'will_get'                  => $will_get,
                'default_currency'          => get_default_currency_code(),
            ];
        }else {
            $default_currency   = Currency::default();
            $exchange_rate      =  $default_currency->rate;
            $will_get           = $receive_money;
            
            $data = [
                'requested_amount'          => $amount,
                'sender_cur_code'           => $currency->currency_code,
                'sender_cur_rate'           => $sender_currency_rate ?? 0,
                'total_charge'              => $fees,
                'total_amount'              => $amount,
                'convert_amount'            => $convert_amount,
                'exchange_rate'             => $exchange_rate,
                'will_get'                  => $will_get,
                'default_currency'          => get_default_currency_code(),
            ];
        }
        return (object) $data;
    }

    public function render() {
        $output = $this->output;
        if(!is_array($output)) throw new Exception("Render Faild! Please call with valid gateway/credentials");
        $common_keys = ['gateway','currency','amount','distribute'];
        foreach($output as $key => $item) {
            if(!array_key_exists($key,$common_keys)) {
                $this->gateway();
                break;
            }
        }
        $distributeMethod = $this->output['distribute'];
        return $this->$distributeMethod($output) ?? throw new Exception("Something went worng! Please try again.");
    }

    public function responseReceive($type = null) {
        $tempData = $this->request_data;

        if(empty($tempData) || empty($tempData['type'])) throw new Exception('Transaction faild. Record didn\'t saved properly. Please try again.');

        $method_name = $tempData['type']."Success";

        if($this->requestIsApiUser()) {
           
            $creator_table = $tempData['data']->creator_table ?? null;
            $creator_id = $tempData['data']->creator_id ?? null;
            $creator_guard = $tempData['data']->creator_guard ?? null;
            $api_authenticated_guards = PaymentGatewayConst::apiAuthenticateGuard();
            if(!array_key_exists($creator_guard,$api_authenticated_guards)) throw new Exception('Request user doesn\'t save properly. Please try again');           
            if($creator_table == null || $creator_id == null || $creator_guard == null) throw new Exception('Request user doesn\'t save properly. Please try again');
            $creator = DB::table($creator_table)->where("id",$creator_id)->first();
            if(!$creator) throw new Exception("Request user doesn\'t save properly. Please try again");
            $api_user_login_guard = $api_authenticated_guards[$creator_guard];
            $this->output['api_login_guard'] = $api_user_login_guard;
            Auth::guard($api_user_login_guard)->loginUsingId($creator->id);
        }
        $currency_id = $tempData['data']->currency ?? "";
        
        $gateway_currency = PaymentGatewayCurrency::find($currency_id);
        
        if(!$gateway_currency) throw new Exception('Transaction faild. Gateway currency not available.');
        
        $validator_data     = [
            'identifier'    => $tempData['data']->user_record,
        ];
        
        $this->request_data = $validator_data;
        $this->gateway();
        $this->output['tempData'] = $tempData;
        $type = $tempData['type'];
        if($type == 'flutterwave'){
            if(method_exists(FlutterwaveTrait::class,$method_name)) {
                return $this->$method_name($this->output);
            }
        }elseif($type == 'stripe'){
            if(method_exists(Stripe::class,$method_name)) {
                return $this->$method_name($this->output);
            }
        }elseif($type == 'sslcommerz'){
            if(method_exists(SslcommerzTrait::class,$method_name)) {
                return $this->$method_name($this->output);
            }
        }elseif($type == 'razorpay'){
            if(method_exists(RazorTrait::class,$method_name)) {
                return $this->$method_name($this->output);
            }
        }else{
            if(method_exists(Paypal::class,$method_name)) {
                return $this->$method_name($this->output);
            }
        }
        throw new Exception("Response method ".$method_name."() does not exists.");
    }

    public function type($type) {
        $this->output['type']  = $type;
        return $this;
    }
    public function requestIsApiUser() {
        $request_source = request()->get('r-source');
        if($request_source != null && $request_source == PaymentGatewayConst::APP) return true;
        return false;
    }
    public function responseReceiveApi($type = null) {
        $tempData = $this->request_data;

        if(empty($tempData) || empty($tempData['type'])){
            return Response::error(['Transaction faild. Record didn\'t saved properly. Please try again.']);
        }

        $method_name = $tempData['type']."Success";
        if($this->requestIsApiUser()) {
            $creator_table = $tempData['data']->creator_table ?? null;
            $creator_id = $tempData['data']->creator_id ?? null;
            $creator_guard = $tempData['data']->creator_guard ?? null;
            $api_authenticated_guards = PaymentGatewayConst::apiAuthenticateGuard();
            if($creator_table != null && $creator_id != null && $creator_guard != null) {
                if(!array_key_exists($creator_guard,$api_authenticated_guards)) throw new Exception('Request user doesn\'t save properly. Please try again');
                $creator = DB::table($creator_table)->where("id",$creator_id)->first();
                if(!$creator) throw new Exception("Request user doesn\'t save properly. Please try again");
                $api_user_login_guard = $api_authenticated_guards[$creator_guard];
                $this->output['api_login_guard'] = $api_user_login_guard;
                Auth::guard($api_user_login_guard)->loginUsingId($creator->id);
            }
        }

        $currency_id = $tempData['data']->currency ?? "";
        $gateway_currency = PaymentGatewayCurrency::find($currency_id);
        if(!$gateway_currency){
            $error = ['error'=>['Transaction faild. Gateway currency not available.']];
            return Response::error($error);
        }

        $validator_data     = [
            'identifier'    => $tempData['data']->user_record,
        ];

        $this->request_data = $validator_data;
        $this->gateway();
        $this->output['tempData'] = $tempData;
        $type = $tempData['type'];
        if($type == 'flutterWave'){
            if(method_exists(FlutterwaveTrait::class,$method_name)) {
                return $this->$method_name($this->output);
            }
        }elseif($type == 'stripe'){
            if(method_exists(Stripe::class,$method_name)) {
                return $this->$method_name($this->output);
            }
        }elseif($type == 'sslcommerz'){
            if(method_exists(SslcommerzTrait::class,$method_name)) {
                return $this->$method_name($this->output);
            }
        }elseif($type == 'razorpay'){
            if(method_exists(RazorTrait::class,$method_name)) {
                return $this->$method_name($this->output);
            }
        }else{
            if(method_exists(Paypal::class,$method_name)) {
                return $this->$method_name($this->output);
            }
        }

        $error = ['error'=>["Response method ".$method_name."() does not exists."]];
        return Response::error($error);

    }

    public function api() {
        $output               = $this->output;
        $output['distribute'] = $this->gatewayDistribute() . "Api";
        $method               = $output['distribute'];
        $response             = $this->$method($output);
        $output['response']   = $response;
        $this->output         = $output;
        return $this;
    }

    public function setTransaction(string $transaction_id) {
        return $this->transaction_id = $transaction_id;
    }

    public function transaction(){
        return $this->transaction_id;
    }

}
