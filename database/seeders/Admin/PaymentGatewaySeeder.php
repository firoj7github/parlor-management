<?php

namespace Database\Seeders\Admin;

use App\Constants\PaymentGatewayConst;
use App\Models\Admin\PaymentGateway;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $payment_gateways = array(
            array('slug' => 'payment-method','code' => '105','type' => 'AUTOMATIC','name' => 'Paypal','title' => 'Paypal Gateway','alias' => 'paypal','image' => 'seeder/paypal.png','credentials' => '[{"label":"Client ID","placeholder":"Enter Client ID","name":"client-id","value":"AbMgZu03hDEAs8aMK96dj52nCFfEEFd2nSffXsdf8NIBbOiogClRVFbsFqxqPjQHeb221XXCrZR2GXyZ"},{"label":"Secret ID","placeholder":"Enter Secret ID","name":"secret-id","value":"EHjAeQn76vtKvJBUipJ54BFqUrcuP4bB01xgbAGAn7q-p5WgtGzj6FFeEzXuTNEVaPtCcP4qKSwQu0sb"}]','supported_currencies' => '["USD"]','crypto' => '0','desc' => NULL,'input_fields' => NULL,'env' => 'SANDBOX','status' => '1','last_edit_by' => '1','created_at' => '2023-10-25 08:58:17','updated_at' => '2023-10-25 09:17:23'),
            array('slug' => 'payment-method','code' => '110','type' => 'AUTOMATIC','name' => 'Flutterwave','title' => 'Flutterwave Gateway','alias' => 'flutterwave','image' => 'seeder/flutterwave.png','credentials' => '[{"label":"Encryption key","placeholder":"Enter Encryption key","name":"encryption-key","value":"FLWSECK_TEST27bee2235efd"},{"label":"Secret key","placeholder":"Enter Secret key","name":"secret-key","value":"FLWSECK_TEST-da35e3dbd28be1e7dc5d5f3519e2ebef-X"},{"label":"Public key","placeholder":"Enter Public key","name":"public-key","value":"FLWPUBK_TEST-e0bc02a00395b938a4a2bed65e1bc94f-X"}]','supported_currencies' => '["NGN"]','crypto' => '0','desc' => NULL,'input_fields' => NULL,'env' => NULL,'status' => '1','last_edit_by' => '1','created_at' => '2023-10-25 09:24:56','updated_at' => '2023-10-25 09:24:56'),
            array('slug' => 'payment-method','code' => '115','type' => 'AUTOMATIC','name' => 'Stripe','title' => 'Stripe Gateway','alias' => 'stripe','image' => 'seeder/stripe.png','credentials' => '[{"label":"Publishable key","placeholder":"Enter Publishable key","name":"publishable-key","value":"pk_test_51NECrlJXLo7QTdMco2E4YxHSeoBnDvKmmi0CZl3hxjGgH1JwgcLVUF3ZR0yFraoRgT7hf0LtOReFADhShAZqTNuB003PnBSlGP"},{"label":"Secret Id","placeholder":"Enter Secret Id","name":"secret-id","value":"sk_test_51NECrlJXLo7QTdMc2x7K5LaDuiS0MGNYHkO9dzzV0Y9XuWNZsXjECFsusjZEnqtxMIjCh3qtogc5sHHwL2oQ083900aFy1k7DE"}]','supported_currencies' => '["AUD"]','crypto' => '0','desc' => NULL,'input_fields' => NULL,'env' => NULL,'status' => '1','last_edit_by' => '1','created_at' => '2023-10-25 09:26:23','updated_at' => '2023-10-25 09:26:23'),
            array('slug' => 'payment-method','code' => '200','type' => 'AUTOMATIC','name' => 'RazorPay','title' => 'Razor Pay Payment Gateway','alias' => 'razorpay','image' => 'seeder/razor-pay.png','credentials' => '[{"label":"Public key","placeholder":"Enter Public key","name":"public-key","value":"rzp_test_B6FCT9ZBZylfoY"},{"label":"Secret Key","placeholder":"Enter Secret Key","name":"secret-key","value":"s4UYHtNwq5TkHSexU5Pnp1pm"}]','supported_currencies' => '["INR"]','crypto' => '0','desc' => NULL,'input_fields' => NULL,'status' => '1','last_edit_by' => '1','created_at' => '2023-08-23 13:21:55','updated_at' => '2023-08-23 13:23:20','env' => 'SANDBOX'),
            array('slug' => 'payment-method','code' => '210','type' => 'AUTOMATIC','name' => 'SSLCommerz','title' => 'SSLCommerz Payment Gateway For Add Money','alias' => 'sslcommerz','image' => 'seeder/sslcommerz.webp','credentials' => '[{"label":"Store Id","placeholder":"Enter Store Id","name":"store-id","value":"appde6513b3970d62c"},{"label":"Store Password","placeholder":"Enter Store Password","name":"store-password","value":"appde6513b3970d62c@ssl"},{"label":"Sandbox Url","placeholder":"Enter Sandbox Url","name":"sandbox-url","value":"https:\\/\\/sandbox.sslcommerz.com"},{"label":"Live Url","placeholder":"Enter Live Url","name":"live-url","value":"https:\\/\\/securepay.sslcommerz.com"}]','supported_currencies' => '["BDT","EUR","GBP","AUD","USD","CAD"]','crypto' => '0','desc' => NULL,'input_fields' => NULL,'status' => '1','last_edit_by' => '1','created_at' => '2023-09-27 16:11:26','updated_at' => '2023-09-27 16:11:53','env' => 'SANDBOX'),
        );
        PaymentGateway::insert($payment_gateways);

        $payment_gateway_currencies = array(
            array('payment_gateway_id' => '1','name' => 'Paypal USD','alias' => 'payment-method-paypal-usd-automatic','currency_code' => 'USD','currency_symbol' => '$','image' => 'seeder/paypal.png','min_limit' => '0.00000000','max_limit' => '0.00000000','percent_charge' => '0.00000000','fixed_charge' => '0.00000000','rate' => '1.00000000','created_at' => '2023-10-25 09:17:23','updated_at' => '2023-10-25 09:28:41'),
            array('payment_gateway_id' => '2','name' => 'Flutterwave NGN','alias' => 'payment-method-flutterwave-ngn-automatic','currency_code' => 'NGN','currency_symbol' => '₦','image' => 'seeder/flutterwave.png','min_limit' => '0.00000000','max_limit' => '0.00000000','percent_charge' => '0.00000000','fixed_charge' => '0.00000000','rate' => '766.00000000','created_at' => '2023-10-25 09:27:33','updated_at' => '2023-10-25 09:27:33'),
            array('payment_gateway_id' => '3','name' => 'Stripe AUD','alias' => 'payment-method-stripe-aud-automatic','currency_code' => 'AUD','currency_symbol' => 'A$','image' => 'seeder/stripe.png','min_limit' => '0.00000000','max_limit' => '0.00000000','percent_charge' => '0.00000000','fixed_charge' => '0.00000000','rate' => '1.53000000','created_at' => '2023-10-25 09:28:02','updated_at' => '2023-10-25 09:28:02'),
            array('payment_gateway_id' => '4','name' => 'RazorPay INR','alias' => 'razorpay-inr-automatic','currency_code' => 'INR','currency_symbol' => '₹','image' => 'seeder/razor-pay.png','min_limit' => '100.00000000','max_limit' => '100000.00000000','percent_charge' => '0.00000000','fixed_charge' => '0.00000000','rate' => '82.87000000','created_at' => '2023-08-23 13:23:21','updated_at' => '2023-08-23 13:26:35'),
            array('payment_gateway_id' => '5','name' => 'SSLCommerz BDT','alias' => 'sslcommerz-bdt-automatic','currency_code' => 'BDT','currency_symbol' => '৳','image' => 'seeder/sslcommerz.webp','min_limit' => '100.00000000','max_limit' => '50000.00000000','percent_charge' => '0.00000000','fixed_charge' => '1.00000000','rate' => '110.64000000','created_at' => '2023-09-27 16:11:53','updated_at' => '2023-09-27 16:12:04'),         
        );
        PaymentGatewayCurrency::insert($payment_gateway_currencies);


    }
}
