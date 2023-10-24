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

        // -----------------
        // PAYPAL (Automatic - Add Money) START
        //------------------

        $data =   array('slug' => 'add-money','code' => '105','type' => 'AUTOMATIC','name' => 'Paypal','title' => 'Paypal Gateway','alias' => 'paypal','image' => NULL,'credentials' => '[{"label":"Client ID","placeholder":"Enter Client ID","name":"client-id","value":null},{"label":"Secret ID","placeholder":"Enter Secret ID","name":"secret-id","value":null}]','supported_currencies' => '["USD","GBP","PHP","NZD","MYR","EUR","CNY","CAD","AUD"]','crypto' => '0','desc' => NULL,'input_fields' => NULL,'env' => 'SANDBOX','status' => '1','last_edit_by' => '1','created_at' => '2023-05-29 11:09:41','updated_at' => '2023-05-29 11:10:05');
        
        $gateway_id = PaymentGateway::insertGetId($data);

        $gateway_currency = array(
            array('payment_gateway_id' => $gateway_id,'name' => 'Paypal CAD','alias' => 'add-money-paypal-cad-automatic','currency_code' => 'CAD','currency_symbol' => '$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.36000000','created_at' => '2023-05-29 11:15:57','updated_at' => '2023-05-29 11:16:02'),
            array('payment_gateway_id' => $gateway_id,'name' => 'Paypal MYR','alias' => 'add-money-paypal-myr-automatic','currency_code' => 'MYR','currency_symbol' => 'RM','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '4.61000000','created_at' => '2023-05-29 11:15:57','updated_at' => '2023-05-29 11:16:02'),
            array('payment_gateway_id' => $gateway_id,'name' => 'Paypal GBP','alias' => 'add-money-paypal-gbp-automatic','currency_code' => 'GBP','currency_symbol' => '£','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '0.81000000','created_at' => '2023-05-29 11:15:57','updated_at' => '2023-05-29 11:16:02'),
            array('payment_gateway_id' => $gateway_id,'name' => 'Paypal USD','alias' => 'add-money-paypal-usd-automatic','currency_code' => 'USD','currency_symbol' => '$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.00000000','created_at' => '2023-05-29 11:15:57','updated_at' => '2023-05-29 11:16:02')
        );
        
        PaymentGatewayCurrency::insert($gateway_currency);

        // -----------------
        // PAYPAL (Automatic - Add Money) END
        //------------------


        // -----------------
        // AD PAY (Manual - Add Money) START
        //------------------

        $data =     array('slug' => 'add-money','code' => '110','type' => 'MANUAL','name' => 'AD PAY','title' => 'AD PAY Gateway','alias' => 'ad-pay','image' => NULL,'credentials' => NULL,'supported_currencies' => '["USD"]','crypto' => '0','desc' => '<h4><strong>Please follow the instructions bellow:</strong></h4><p><strong>Fill up all field with correct information.</strong></p>','input_fields' => '[{"type":"file","label":"Screenshoot","name":"screenshoot","required":true,"validation":{"max":"10","mimes":["jpg","png","webp","svg"],"min":0,"options":[],"required":true}},{"type":"text","label":"Transaction ID","name":"transaction_id","required":true,"validation":{"max":"60","mimes":[],"min":"0","options":[],"required":true}},{"type":"text","label":"Full Name","name":"full_name","required":true,"validation":{"max":"30","mimes":[],"min":"0","options":[],"required":true}}]','env' => NULL,'status' => '1','last_edit_by' => '1','created_at' => NULL,'updated_at' => NULL);
        
        $gateway_id = PaymentGateway::insertGetId($data);


        $gateway_currency = array(
            array('payment_gateway_id' => $gateway_id,'name' => 'AD PAY USD','alias' => 'add-money-ad-pay-usd-manual','currency_code' => 'USD','currency_symbol' => '$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.00000000','created_at' => '2023-05-29 11:22:38','updated_at' => '2023-05-29 11:22:38'),
        );

        PaymentGatewayCurrency::insert($gateway_currency);

        // -----------------
        // AD PAY (Manual - Add Money) END
        //------------------


        // -----------------
        // AD PAY (withdraw) (Manual - Money Out) START
        //------------------

        $data =  array('slug' => 'money-out','code' => '115','type' => 'MANUAL','name' => 'AD PAY (withdraw)','title' => 'AD PAY (withdraw) Gateway','alias' => 'ad-pay-withdraw','image' => NULL,'credentials' => NULL,'supported_currencies' => '["USD"]','crypto' => '0','desc' => '<h4><strong>Please follow the instructions bellow:</strong></h4><p><strong>Fill up all field with correct information.</strong></p>','input_fields' => '[{"type":"file","label":"Screenshoot","name":"screenshoot","required":true,"validation":{"max":"10","mimes":["jpg","png","webp","svg"],"min":0,"options":[],"required":true}},{"type":"text","label":"Bank Name","name":"bank_name","required":true,"validation":{"max":"60","mimes":[],"min":"0","options":[],"required":true}},{"type":"text","label":"A\\/C No","name":"a_c_no","required":true,"validation":{"max":"60","mimes":[],"min":"0","options":[],"required":true}}]','env' => NULL,'status' => '1','last_edit_by' => '1','created_at' => NULL,'updated_at' => NULL);
        
        $gateway_id = PaymentGateway::insertGetId($data);

        $gateway_currency = array(
            array('payment_gateway_id' => $gateway_id,'name' => 'AD PAY (withdraw) USD','alias' => 'money-out-ad-pay-withdraw-usd-manual','currency_code' => 'USD','currency_symbol' => '$','image' => NULL,'min_limit' => '1.00000000','max_limit' => '5000.00000000','percent_charge' => '2.00000000','fixed_charge' => '0.00000000','rate' => '1.00000000','created_at' => '2023-05-29 12:11:20','updated_at' => '2023-05-29 12:11:20'),
        );

        PaymentGatewayCurrency::insert($gateway_currency);

        // -----------------
        // AD PAY (withdraw) (Manual - Money Out) END
        //------------------



    }
}
