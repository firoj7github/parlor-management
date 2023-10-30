<?php
namespace App\Constants;
use Illuminate\Support\Str;

class PaymentGatewayConst {

    const AUTOMATIC = "AUTOMATIC";
    const MANUAL    = "MANUAL";
    const PAYMENTMETHOD  = "Payment Method";
    const ACTIVE    =  true;

    const MANUA_GATEWAY = 'manual';
    const PAYPAL        = 'paypal';
    const STRIPE        = 'stripe';
    const FLUTTER_WAVE  = 'flutterwave';
    const RAZORPAY      = 'razorpay';
    const SSLCOMMERZ    = 'sslcommerz';

    const APP           = "APP";
    const ENV_SANDBOX       = "SANDBOX";
    const ENV_PRODUCTION    = "PRODUCTION";

    public static function payment_method_slug() {
        return Str::slug(self::PAYMENTMETHOD);
    }
    public static function register($alias = null) {
        $gateway_alias          = [
            self::PAYPAL        => "paypalInit",
            self::STRIPE        => "stripeInit",
            self::MANUA_GATEWAY => "manualInit",
            self::FLUTTER_WAVE  => 'flutterwaveInit',
            self::RAZORPAY      => 'razorInit',
            self::SSLCOMMERZ    => 'sslcommerzInit'

        ];

        if($alias == null) {
            return $gateway_alias;
        }

        if(array_key_exists($alias,$gateway_alias)) {
            return $gateway_alias[$alias];
        }
        return "init";
    }
    public static function apiAuthenticateGuard() {
        return [
            'api'   => 'web',
        ];
    }

}
