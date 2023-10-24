<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\Admin\Language;
use App\Models\Admin\UsefullLink;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class UsefullLinkController extends Controller
{
    public function index(){
        $page_title     = "Useful Links";
        $languages       = Language::get();
        $useful_links   = UsefullLink::get();

        return view('admin.sections.useful-links.index',compact(
            'page_title',
            'languages',
            'useful_links'
        ));
    }

    //store method
    public function store(Request $request){
        $section_data['title']['language']  = $this->contentValidate($request,['title'     => 'required|string|max:255'],'link-add');
        if($section_data['title']['language'] instanceof RedirectResponse) {
            return $section_data['title']['language'];
        }

        $section_data['content']['language']  = $this->contentValidate($request,['content'   => 'required|string|max:6000'],'link-add');
        if($section_data['content']['language'] instanceof RedirectResponse) {
            return $section_data['content']['language'];
        }

        $validator = Validator::make($request->all(),[
            'slug'          => "required|string|max:200",
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput()->with('modal','link-add');

        $validated = $validator->validate();
        $validated['slug']   = Str::slug($validated['slug']);

        // check slug available is not
        if(UsefullLink::where('slug',$validated['slug'])->exists()) {
            return back()->withErrors($validator)->withInput()->with('modal','link-add');
        }

        $section_data['type']       = GlobalConst::UNKNOWN;
        $validated['url']           = $validated['slug'];
        $validated['editable']      = true;

        $section_data = array_merge($section_data,$validated);

        try{
            UsefullLink::create($section_data);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Useful link added successfully!']]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param object $request
     * @param array $basic_field_name
     * @return array $language_wise_data
     */

    public function edit($slug){
        $page_title    = "Edit Useful Link";
        $useful_link  = UsefullLink::where('slug',$slug)->first();
        if(!$useful_link) return back()->with(['error' => ['Link not found!']]);
        $languages = Language::get();

        return view('admin.sections.useful-links.edit',compact(
            'page_title',
            'useful_link',
            'languages'
        ));
    }
    /**
     * Update the specified resource in storage
     * @param object $request
     * @param array $basic_field_name
     * @return array $language_wise_data
     */
    public function update(Request $request,$slug){
        $useful_link = UsefullLink::where('slug',$slug)->first();
        if(!$useful_link) return back()->with(['error'  => ['Link not found!']]);
        
        $section_data['title']['language']    = $this->contentValidate($request,['title' => 'required|string|max:255']);
        $section_data['content']['language']  = $this->contentValidate($request,['content' => 'required|string|max:6000']);

        try{
            $useful_link->update($section_data);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return redirect()->route('admin.usefull.links.index')->with(['success' => ['Useful link updated successfully!']]);
    }
    public function delete(Request $request){
        $request->validate([
            'target'  => 'required|integer'
        ]);

        $useful_link = UsefullLink::find($request->target);

        try{
            $useful_link->delete();
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Useful link deleted successfully!']]);

    }

    public function statusUpdate(Request $request){
        $validator  = Validator::make($request->all(),[
            'status'      => 'required|boolean',
            'data_target' => 'required|integer',
        ]);

        if($validator->fails()){
            return Response::error(['error'=> $validator->errors()],null,400);
        }
        $validated = $validator->validate();

        try{
            $link  = UsefullLink::find($validated['data_target']);
            if($link){
                $link->update([
                    'status' => ($validated['status'] == true) ? false : true,
                ]);
            }
        }catch(Exception $e){
            return Response::error(['error' => ['Something went wrong! Please try again.']],null,500);
        }

        return Response::success(['success' => ['Useful link status updated successfully!']],null,200);

    }

    
    /**
     * Method for validate request data and re-decorate language wise data
     * @param object $request
     * @param array $basic_field_name
     * @return array $language_wise_data
     */
    public function contentValidate($request,$basic_field_name,$modal = null) {
        $languages = Language::get();

        $current_local = get_default_language_code();
        $validation_rules = [];
        $language_wise_data = [];
        foreach($request->all() as $input_name => $input_value) {
            foreach($languages as $language) {
                $input_name_check = explode("_",$input_name);
                $input_lang_code = array_shift($input_name_check);
                $input_name_check = implode("_",$input_name_check);
                if($input_lang_code == $language['code']) {
                    if(array_key_exists($input_name_check,$basic_field_name)) {
                        $langCode = $language['code'];
                        if($current_local == $langCode) {
                            $validation_rules[$input_name] = $basic_field_name[$input_name_check];
                        }else {
                            $validation_rules[$input_name] = str_replace("required","nullable",$basic_field_name[$input_name_check]);
                        }
                        $language_wise_data[$langCode][$input_name_check] = $input_value;
                    }
                    break;
                } 
            }
        }
        if($modal == null) {
            $validated = Validator::make($request->all(),$validation_rules)->validate();
        }else {
            $validator = Validator::make($request->all(),$validation_rules);
            if($validator->fails()) {
                return back()->withErrors($validator)->withInput()->with("modal",$modal);
            }
            $validated = $validator->validate();
        }

        return $language_wise_data;
    }
}
