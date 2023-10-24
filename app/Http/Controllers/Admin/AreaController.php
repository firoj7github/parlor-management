<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Admin\Area;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AreaController extends Controller
{
    /**
     * Method for show the setup area page
     * return view
     */
    public function index(){
        $page_title     = "Setup Area";
        $areas          = Area::orderBYDESC('id')->paginate(10);

        return view('admin.sections.setup-area.index',compact(
            'page_title',
            'areas'
        ));
    }
    /**
     * Method for store Remittance Bank 
     * @param string 
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request){
        $validator     = Validator::make($request->all(),[
            'name'     => 'required|string',
        ]);

        if($validator->fails()) return back()->withErrors($validator)->withInput()->with("modal","add-remittance-bank");

        $validated     = $validator->validate();
        $validated['slug']   = Str::slug($request->name);
        if(Area::where('name',$validated['name'])->exists()){
            throw ValidationException::withMessages([
                'name'   => 'Area already exists',
            ]);
        }
        try{
            Area::create($validated);
        }catch(Exception $e){
            return back()->with(['error'  => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Area Added Successfully']]);
    }
    /**
     * Method for update Remittance bank 
     * @param string
     * @param \Illuminate\Http\Request $request 
     */
    public function update(Request $request){

        $validator = Validator::make($request->all(),[
            'target'        => 'required|numeric|exists:areas,id',
            'edit_name'     => 'required|string|max:80|'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with("modal","edit-remittance-bank");
        }

        $validated = $validator->validate();
        
        $slug      = Str::slug($request->edit_name);
        $validated = replace_array_key($validated,"edit_");
        $validated = Arr::except($validated,['target']);
        $validated['slug']   = $slug;

        if(Area::where('name',$validated['name'])->exists()){
            throw ValidationException::withMessages([
                'name'    => 'Remittance Bank already exists',
            ]);
        }
        $remittance_bank = Area::find($request->target);
        
        try{
            $remittance_bank->update($validated);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Area updated successfully!']]);

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
           $area = Area::find($request->target);
    
        try {
            $area->delete();
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Area Deleted Successfully!']]);
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


        $remittance_banks = Area::find($validated['data_target']);

        try{
            $remittance_banks->update([
                'status'        => ($validated['status']) ? false : true,
            ]);
        }catch(Exception $e) {
            $errors = ['error' => ['Something went wrong! Please try again.'] ];
            return Response::error($errors,null,500);
        }

        $success = ['success' => ['Area status updated successfully!']];
        return Response::success($success);
    }
}
