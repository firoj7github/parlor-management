<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentGatewayCurrency;
use App\Models\Admin\PaymentGateway;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Constants\PaymentGatewayConst;
use App\Http\Helpers\Response;

class PaymentGatewaysController extends Controller
{

    /**
     * Function For Redirect Slug Wise Current Function
     * @param string $param
     * @param string|array|object $param_two
     * @param string|array|object $param_three
     * @return method
     */
    public function getSolution($param, $param_two = null, $param_three = null) {
        return $this->$param($param_two,$param_three);
    }


    /**
     * Register Slug And Type Wise All Function
     * @param string $type
     * @return array
     *
     */
    public function registerSlugTypes($type) {
        $slug_types = [
            'view' => [
                'payment-method' => [
                    'automatic' => 'automaticPaymentMethodView',
                ],
            ],
            'edit'  => [
                'payment-method' => [
                    'automatic' => 'automaticPaymentMethodEdit',
                ],
            ],
            'update'    => [
                'payment-method' => [
                    'automatic' => 'automaticPaymentMethodUpdate',
                ],
            ],
            'store'     => [
                'payment-method' => [
                    'automatic' => 'automaticPaymentMethodStore',
                ],
            ]
        ];

        return $slug_types[$type];
    }


    /**
     * Distribute Functions Based on slug and type
     * @param string $slug
     * @param string $type
     * @return Function
     */

    public function paymentGatewayView($slug,$type) {
        $view_slug_types = $this->registerSlugTypes('view');

        if(!array_key_exists($slug,$view_slug_types) || !array_key_exists($type,$view_slug_types[$slug])) {
            abort(404);
        }

        return $this->getSolution($view_slug_types[$slug][$type]);
    }


    /**
     * Display Add Money Automatic Gateways
     * @return view
     */
    public function automaticPaymentMethodView() {
        $page_title = "Automatic Payment Method";
        $payment_gateways = PaymentGateway::paymentMethod()->automatic()->get();
        return view('admin.sections.payment-gateways.payment-method.automatic.index',compact(
            'page_title',
            'payment_gateways',
        ));
    }



    /**
     * Distribute The Specific Function Based on slug and type for View
     * @param string $slug
     * @param string $type
     * @param string $alias
     * @return method
     */
    public function paymentGatewayEdit($slug, $type, $alias) {
        $edit_slug_types = $this->registerSlugTypes('edit');

        if(!array_key_exists($slug,$edit_slug_types) || !array_key_exists($type,$edit_slug_types[$slug])) {
            abort(404);
        }

        return $this->getSolution($edit_slug_types[$slug][$type],$alias);
    }


    /**
     * Display The Edit Page of Add Money Automatic Gateway
     * @return view
     *
     */
    public function automaticPaymentMethodEdit($alias) {
        $page_title = "Automatic Payment Method Edit";
        $gateway = PaymentGateway::paymentMethod()->gateway($alias)->firstOrFail();
        return view('admin.sections.payment-gateways.payment-method.automatic.edit',compact(
            'page_title',
            'gateway',
        ));
    }

    /**
     * Distribute The Specific Function Based on slug and type for Store
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     * @param string $type
     * @return method
     */
    public function paymentGatewayStore(Request $request,$slug, $type) {
        $edit_slug_types = $this->registerSlugTypes('store');

        if(!array_key_exists($slug,$edit_slug_types) || !array_key_exists($type,$edit_slug_types[$slug])) {
            abort(404);
        }

        return $this->getSolution($edit_slug_types[$slug][$type],$request);
    }


    /**
     * Store New Add Money Automatic Gateway
     * @param \Illuminate\Http\Request $request
     */
    public function automaticPaymentMethodStore(Request $request) {
        $gateway_name = $request->gateway_name;
        $validator = Validator::make($request->all(),[
            'gateway_name'              => ['required','string','max:60',Rule::unique('payment_gateways','alias')->where(function($query) use ($gateway_name) {
                $alias = Str::slug($gateway_name);
                $query->where('slug',PaymentGatewayConst::payment_method_slug())->where('type',PaymentGatewayConst::AUTOMATIC)->where('alias',$alias);
            })],
            'gateway_title'             => 'required|string|max:60',
            'supported_currencies'      => 'required|array',
            'supported_currencies.*'    => 'string|max:30',
            'title'                     => 'required|array',
            'title.*'                   => 'string|max:60',
            'name'                      => 'required|array',
            'name.*'                    => 'string|max:60',
            'value'                     => 'nullable|array',
            'value.*'                   => 'nullable|string|max:255',
            'image'                     => 'nullable|image|mimes:png,jpg,jpeg,svg,webp',
        ]);

        $validator->after(function ($validator) use ($gateway_name) {
            // Search Gateway is unique or not
            if(PaymentGateway::paymentMethod()->automatic()->gateway(Str::slug($gateway_name))->exists()) {
                $validator->errors()->add(
                    'gateway_name', 'The gateway name has already been taken.'
                );
            }
        });

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('modal','automatic-payment-method');
        }
        $validated = $validator->validate();

        // Generating input fields collection
        $input_fields = [];
        foreach($validated['title'] as $key => $title) {
            $input_fields[] = [
                'label'         => $title,
                'placeholder'   => "Enter " . $title,
                'name'          => ($validated['name'][$key] == null) ? Str::slug($title) : Str::slug($validated['name'][$key]),
                'value'         => $validated['value'][$key] ?? "",
            ];
        }
        $validated['credentials'] = $input_fields;

        $validated['slug']          = Str::slug("Payment Method");
        $validated['type']          = "AUTOMATIC";
        $validated['name']          = $validated['gateway_name'];
        $validated['title']         = $validated['gateway_title'];
        $validated['alias']         = Str::slug($validated['gateway_name']);
        $validated['last_edit_by']  = Auth::user()->id;

        $validated = Arr::except($validated,['value','gateway_name','gateway_title']);

        $last_record_of_max_code = PaymentGateway::max('code') ?? 100;
        $validated['code']  = set_payment_gateway_code($last_record_of_max_code);

        // Check Image File is Available or not
        if($request->hasFile('image')) {
            $image = get_files_from_fileholder($request,'image');
            $upload = upload_files_from_path_dynamic($image,'payment-gateways');
            $validated['image'] = $upload;
        }

        try{
            PaymentGateway::create($validated);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }

        return back()->with(['success' => ['Payment gateway added successfully!']]);
    }

    /**
     * Function for update payment gateway status active/deactivate
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function paymentGatewayStatusUpdate(Request $request) {

        $validator = Validator::make($request->all(),[
            'status'                    => 'required|boolean',
            'data_target'               => 'required|string',
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }
        $validated = $validator->validate();
        $item_id = $validated['data_target'];

        $payment_gateway = PaymentGateway::find($item_id);
        if(!$payment_gateway) {
            $error = ['error' => ['Payment gateway not found!.']];
            return Response::error($error,null,404);
        }

        try{
            $payment_gateway->update([
                'status' => ($validated['status'] == true) ? false : true,
            ]);
        }catch(Exception $e) {
            $error = ['error' => ['Something went wrong!. Please try again.']];
            return Response::error($error,null,500);
        }

        $success = ['success' => ['Payment gateway status updated successfully!']];
        return Response::success($success,null,200);
    }


    /**
     * Distribute The Specific Function Based on slug and type for Update
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     * @param string $type
     * @param string $alias
     * @return method
     */
    public function paymentGatewayUpdate(Request $request,$slug, $type, $alias) {
        $edit_slug_types = $this->registerSlugTypes('update');

        if(!array_key_exists($slug,$edit_slug_types) || !array_key_exists($type,$edit_slug_types[$slug])) {
            abort(404);
        }

        return $this->getSolution($edit_slug_types[$slug][$type],$request,$alias);
    }

    /**
     * Function for Automatic Add Money Update Based on alias
     * @param \Illuminate\Http\Request $request
     * @param string $alias
     */
    public function automaticPaymentMethodUpdate(Request $request,$alias) {
        $validated_gateway = Validator::make(['alias' => $alias, 'mode' => $request->mode],[
            'alias'     => 'exists:payment_gateways',
            'mode'      => "required|string|in:".PaymentGatewayConst::ENV_SANDBOX.",".PaymentGatewayConst::ENV_PRODUCTION,
        ],[
           'alias.exists'   => "Selected payment gateway is invalid!",
        ])->validate();

        $gateway = PaymentGateway::paymentMethod()->automatic()->gateway($alias)->first();
        $gateway_currencies = $gateway->currencies()->get();
        $available_currencies = $gateway_currencies->pluck("currency_code")->toArray();

        $credentials_validation_rules = [];
        $credentials = $gateway->credentials;
        foreach($credentials as $values) {
            $values = (array) $values;
            $credentials_validation_rules[$values['name']] = "nullable|string";
        }

        $credentials_input_fields = array_keys($credentials_validation_rules);
        $validated_credentials = Validator::make($request->only($credentials_input_fields),$credentials_validation_rules)->validate();

        $credentials_array = json_decode(json_encode($credentials),true);
        foreach($credentials_array as $key => $item) {
            foreach($validated_credentials as $input_name => $value) {
                if($input_name == $item['name']) {
                    $item['value'] = $value;
                }
                $credentials_array[$key] = $item;
            }
        }

        try{
            $image = $gateway->image;
            if($request->hasFile('image')) {
                $image = get_files_from_fileholder($request,'image');
                $upload_image = upload_files_from_path_dynamic($image,'payment-gateways',$gateway->image);
                $image = $upload_image;
            }
            $gateway->update([
                'credentials'   => $credentials_array,
                'image'         => $image,
                'env'           => $validated_gateway['mode'],
            ]);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }

        $gateway_supported_currency = json_decode(json_encode($gateway->supported_currencies),true);

        $form_input_fields_validation_rules = [
            'rate'              => 'nullable|numeric',
            'currency_symbol'   => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,svg,webp',
        ];
        $input_field_base_name = "gateway_currency";

        $gateway_currency_validation_rules = [];
        foreach($request->only([$input_field_base_name]) as $item) {
            if(!is_array($item)) break;
            foreach($item as $currency => $fields_value) {
                if(!is_array($fields_value)) break;
                if(in_array($currency,$gateway_supported_currency)) {
                    foreach($fields_value as $input_key => $input_value) {
                        if(array_key_exists($input_key,$form_input_fields_validation_rules)) {
                            $validation_rule_key = $input_field_base_name.".".$currency.".".$input_key;
                            $validation_rule = $form_input_fields_validation_rules[$input_key];

                            $gateway_currency_validation_rules[$validation_rule_key]    = $validation_rule;
                        }
                    }
                }
            }
        }

        $validated = Validator::make($request->only($input_field_base_name),$gateway_currency_validation_rules)->validate();
        $data_ready_to_work  = [];
        foreach($validated[$input_field_base_name] ?? [] as $currency => $item) {

            $item['currency_code']      = $currency;
            $item['name']               = $gateway->name . " " . $currency;
            $item['alias']              = PaymentGatewayConst::payment_method_slug() . "-" . Str::slug($gateway->name . " " . $currency . " " . $gateway->type);
            $item['payment_gateway_id'] = $gateway->id;
            $item['rate']               = $item['rate'] ?? 1;
            $item['image']              = null;

            if(in_array($currency,$available_currencies)) {
                $old_image      = $gateway_currencies->where('currency_code',$currency)->first()->image;
                $item['image']  = $old_image;
            }

            if($request->hasFile($input_field_base_name.".".$currency."."."image")) {
                $image = get_files_from_fileholder($request,$input_field_base_name.".".$currency."."."image");
                if(in_array($currency,$available_currencies)) {
                    $old_image = $gateway_currencies->where('currency_code',$currency)->first()->image;
                    $upload_image = upload_files_from_path_dynamic($image,'payment-gateways',$old_image);
                }else {
                    $upload_image = upload_files_from_path_dynamic($image,'payment-gateways');
                }
                $item['image']  = $upload_image;
            }

            $data_ready_to_work[] = $item;

        }

        try{
            PaymentGatewayCurrency::where('payment_gateway_id',$gateway->id)->upsert($data_ready_to_work,['alias']);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }

        return back()->with(['success' => ['Information updated successfully!']]);

    }


    /**
     * Distribute The Specific Function Based on slug and type for Create
     * @param string $slug
     * @param string $type
     * @return method
     */
    public function paymentGatewayCreate($slug,$type) {
        $edit_slug_types = $this->registerSlugTypes('create');

        if(!array_key_exists($slug,$edit_slug_types) || !array_key_exists($type,$edit_slug_types[$slug])) {
            abort(404);
        }

        return $this->getSolution($edit_slug_types[$slug][$type]);
    }
   

    public function remove(Request $request) {
        $validated = Validator::make($request->all(),[
            'target'        => 'required|integer|exists:payment_gateways,id',
        ],[
            'target.exists'     => 'Selected payment gateway is invalid!',
        ])->validate();

        // Delete payment gateway currency
        try{
            $gateway = PaymentGateway::find($validated['target']);
            $gateway->currencies()->delete();
            $gateway->delete();

            
            if($gateway->image != null) {
                $image_link = get_files_path('payment-gateways') . "/" . $gateway->image;
                delete_file($image_link);
            }
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }

        return back()->with(['success' => ['Payment gateway deleted successfully!']]);
    }
}
