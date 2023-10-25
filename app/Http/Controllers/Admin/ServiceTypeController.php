<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\ServiceType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ServiceTypeController extends Controller
{
    /**
     * Method for view service type page
     * @return view
     */
    public function index(){
        $page_title     = "Service Type";
        $service_types  = ServiceType::orderBYDESC('id')->paginate(15);

        return view('admin.sections.service-type.index',compact(
            'page_title',
            'service_types'
        ));
    }
    /**
     * Method for store Remittance Bank 
     * @param string 
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request){
        $validator      = Validator::make($request->all(),[
            'name'      => 'required|string',
            'price'     => 'required',
        ]);

        if($validator->fails()) return back()->withErrors($validator)->withInput()->with("modal","add-service-type");

        $validated     = $validator->validate();
        $validated['slug']   = Str::slug($request->name);
        if(ServiceType::where('name',$validated['name'])->exists()){
            throw ValidationException::withMessages([
                'name'   => 'Service Type already exists',
            ]);
        }
        try{
            ServiceType::create($validated);
        }catch(Exception $e){
            return back()->with(['error'  => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Service Type Added Successfully']]);
    }
    /**
     * Method for update Remittance bank 
     * @param string
     * @param \Illuminate\Http\Request $request 
     */
    public function update(Request $request){

        $validator = Validator::make($request->all(),[
            'target'        => 'required|numeric|exists:service_types,id',
            'edit_name'     => 'required|string|max:80|',
            'edit_price'    => 'required'

        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with("modal","edit-service-type");
        }

        $validated = $validator->validate();
        
        $slug      = Str::slug($request->edit_name);
        $validated = replace_array_key($validated,"edit_");
        $validated = Arr::except($validated,['target']);
        $validated['slug']   = $slug;

        if(ServiceType::where('name',$validated['name'])->exists()){
            throw ValidationException::withMessages([
                'name'    => 'Service Type already exists',
            ]);
        }
        $service_type = ServiceType::find($request->target);
        
        try{
            $service_type->update($validated);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Service Type updated successfully!']]);

    }
    /**
     * Method for delete Remittance Bank
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function delete(Request $request){
        $request->validate([
            'target'    => 'required|numeric|',
        ]);
           $service_type = ServiceType::find($request->target);
    
        try {
            $service_type->delete();
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Service Type Deleted Successfully!']]);
    }
    /**
     * Method for status update for remittance bank
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function statusUpdate(Request $request) {
        $validator = Validator::make($request->all(),[
            'data_target'       => 'required|numeric|exists:areas,id',
            'status'            => 'required|boolean',
        ]);

        if($validator->fails()) {
            $errors = ['error' => $validator->errors() ];
            return Response::error($errors);
        }

        $validated = $validator->validate();


        $service_type = ServiceType::find($validated['data_target']);

        try{
            $service_type->update([
                'status'        => ($validated['status']) ? false : true,
            ]);
        }catch(Exception $e) {
            $errors = ['error' => ['Something went wrong! Please try again.'] ];
            return Response::error($errors,null,500);
        }

        $success = ['success' => ['Service Type status updated successfully!']];
        return Response::success($success);
    }
}
