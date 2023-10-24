<?php

namespace App\Http\Controllers\User;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentGateway;
use App\Constants\PaymentGatewayConst;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Traits\PaymentGateway\CommonTrait;
use App\Traits\PaymentGateway\PaypalTrait;
use App\Models\Admin\PaymentGatewayCurrency;


class AddMoneyController extends Controller
{
    use PaypalTrait, CommonTrait;
    public $gateway;
    public $request;
    public function addMoneyRequest(Request $request)
    {
        if ($request->isMethod("POST")) {
            $data = $request->all();
            $validationRules = [
                'payment_gateway' => 'required',
                'amount' => 'required|numeric',
                'currency_code' => 'required'
            ];
            //Validation message
            $validationMessage = [
                'payment_gateway.required' => 'Payment gateway is required',
                'amount.required' => 'Amount is required',
                'currency_code.required' => 'Code is required'
            ];
            $validator = Validator::make($data, $validationRules, $validationMessage);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator);
            }
            # Check limit
            $gateway = DB::table('payment_gateways')->where('code', '=', $data['payment_gateway'])->select('code', 'type', 'id', 'alias')->first();
            $currency = PaymentGatewayCurrency::where('payment_gateway_id', $gateway->id)->where('currency_code', $data['currency_code'])->select('currency_code', 'id', 'min_limit', 'max_limit')->first();
            if ($data['amount'] > $currency->max_limit) {
                return redirect()->back()->with(['error' => ['Request amount exceed max limit.']]);
            } elseif ($data['amount'] < $currency->min_limit) {
                return redirect()->back()->with(['error' => ['Please enter minimum limit.']]);
            }
            $gatewayType = $this->getPaymentGatewayType($request);
            return $this->gatewayDistributebyType($gatewayType, $request);
        }
        # Show Ad Monery form
        $payment_gateways = PaymentGateway::has('currencies')->active()->addMoney()->get();
        $payment_gateways = json_decode(json_encode($payment_gateways), true);
        $add_money_transactions = Transaction::with('payment_gateway')->where('user_id', '=', auth()->user()->id)->orderBy('created_at', 'desc')->take(4)->get();
        $add_money_transactions = json_decode(json_encode($add_money_transactions), true);
        return view('frontend.pages.user.add_moeny', compact('payment_gateways', 'add_money_transactions'));
    }
    public function automaticGatewayControl($request)
    {
        $data = CommonTrait::basicData($request);
        switch ($data) {
            case $data['pmntGateway']->alias == "paypal":
                $this->request = $request;
                $config = PaypalTrait::config($this->gateway, $request);
                $response = PaypalTrait::initialize($config, $request);
                return $response;
                break;
            case $data['pmntGateway']->alias == "stripe":
                return view('frontend.pages.user.payment.stripe', compact('data'));
                break;
            case $data['pmntGateway']->alias == "paystack":
                return view('frontend.pages.user.payment.paystack', compact('data'));
                break;
            case $data['pmntGateway']->alias == "coinbase":
                //dd('coinbase');
                return view('frontend.pages.user.payment.coinbase', compact('data'));
                break;
        }
    }


    public function gatewayDistributebyType($type, $request)
    {
        $registeredMethodType = [
            PaymentGatewayConst::AUTOMATIC => "automaticGatewayControl",
        ];
        $method = $registeredMethodType[$type];
        return $this->$method($request);
    }

    public function getPaymentGatewayType(Request $request)
    {
        $data = $request->all();
        // Need validation
        if (!isset($data['payment_gateway'])) {
            return back()->with(['error' => ['Please select a gateway']]);
        }
        $gateway = PaymentGateway::where('code', $data['payment_gateway'])->first();
        $gateway = json_decode(json_encode($gateway));
        if (!$gateway) {
            return back()->with(['error' => ['Please select a gateway']]);
        }
        $this->gateway = $gateway;
        return $gateway->type;
    }

    public function addMoneyLogs(Request $request)
    {
        $title = "Add Money Log";
        $add_moeny_logs = "UserAddMoneryLogsTable";
        return view('frontend.pages.user.add_moeny_logs', compact('add_moeny_logs'));
    }
}
