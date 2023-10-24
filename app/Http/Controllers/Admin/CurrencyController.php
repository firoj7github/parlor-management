<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Currency;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Helpers\Response;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "Setup Currency";
        $currencies = Currency::orderByDesc('default')->paginate(10);
        return view('admin.sections.currency.index',compact(
            'page_title',
            'currencies',
        ));
    }
    /**
     * Update Currency Status
     */
    public function statusUpdate(Request $request) {
        $validator = Validator::make($request->all(),[
            'status'                    => 'required|boolean',
            'data_target'               => 'required|string',
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }
        $validated = $validator->safe()->all();
        $currency_code = $validated['data_target'];

        $currency = Currency::where('code',$currency_code)->first();
        if(!$currency) {
            $error = ['error' => ['Currency record not found in our system.']];
            return Response::error($error,null,404);
        }

        try{
            $currency->update([
                'status' => ($validated['status'] == true) ? false : true,
            ]);
        }catch(Exception $e) {
            $error = ['error' => ['Something went wrong!. Please try again.']];
            return Response::error($error,null,500);
        }

        $success = ['success' => ['Currency status updated successfully!']];
        return Response::success($success,null,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $target = $request->target ?? $request->currency_code;
        $currency = Currency::where('code',$target)->first();
        if(!$currency) {
            return back()->with(['warning' => ['Currency not found!']]);
        }
        $request->merge(['old_flag' => $currency->flag]);

        $validator = Validator::make($request->all(),[
            'currency_type'      => 'required|string',
            'currency_country'   => 'required|string',
            'currency_name'      => 'required|string',
            'currency_code'      => ['required','string',Rule::unique('currencies','code')->ignore($currency->id)],
            'currency_symbol'    => 'required|string',
            'currency_target'    => 'nullable|string',
        ]);
        if($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('modal','currency_edit');
        }
        $validated = $validator->validate();
        $validated = Arr::except($validated,['currency_role','currency_flag','currency_option']);

        if($request->hasFile('currency_flag')) {
            try{
                $image = get_files_from_fileholder($request,'currency_flag');
                $uploadFlag = upload_files_from_path_dynamic($image,'currency-flag',$currency->flag);
                $validated['currency_flag'] = $uploadFlag;
            }catch(Exception $e) {
                return back()->withErrors($validator)->withInput()->with(['error' => ['Image file upload faild!']]);
            }
        }
        $validated = replace_array_key($validated,"currency_");
        try{
            $currency->update($validated);
        }catch(Exception $e) {
            return back()->withErrors($validator)->withInput()->with(['error' => ['Something went wrong! Please try again.']]);
        }

        return back()->with(['success' => ['Successfully updated the information.']]);
    }
}
