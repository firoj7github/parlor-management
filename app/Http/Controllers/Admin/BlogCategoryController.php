<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\Language;
use App\Constants\LanguageConst;
use App\Models\Admin\BlogCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlogCategoryController extends Controller
{
    /**
     * Method for show category information
     * @param string 
     * @return view
     */
    public function index(){
        $page_title     = "Blog Categories";
        $categories     = BlogCategory::orderByDesc('id')->paginate(10);
        $languages      = Language::get();

        return view('admin.sections.setup-sections.blog-category.index',compact(
            'page_title',
            'categories',
            'languages'
        ));
    }
    /**
     * Method for store category
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request){
        $basic_field_name = [
            'name' => 'required|string',
        ];
        $data['language']  = $this->contentValidate($request,$basic_field_name);
        // make slug
        $not_removable_lang = LanguageConst::NOT_REMOVABLE;
        $slug_text          = $data['language'][$not_removable_lang]['name'] ?? "";
        if($slug_text == "") {
            $slug_text = $data['language'][get_default_language_code()]['name'] ?? "";
            if($slug_text == "") {
                $slug_text = Str::uuid();
            }
        }
        $slug = Str::slug(Str::lower($slug_text));
        if(BlogCategory::where('slug',$slug)->exists()) return back()->with(['error' => ['Name is similar. Please update/change this Name']]);
        try{
            BlogCategory::updateOrCreate(['slug' => $slug],['name'=> $data]);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ["Category added successfully."]]);
    }
    /**
     * Method for update the status of category
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function statusUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'status'                    => 'required|boolean',
            'data_target'               => 'required|string',
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error, null, 400);
        }
        $validated = $validator->validate();

        
        try {
            $category = BlogCategory::find($validated['data_target']);
            if($category) {
                $category->update([
                    'status'    => ($validated['status'] == true) ? false : true,
                ]);
            }
        } catch (Exception $e) {
            $error = ['error' => ['Something went wrong!. Please try again.']];
            return Response::error($error, null, 500);
        }

        $success = ['success' => ['Category status updated successfully!']];
        return Response::success($success, null, 200);
    }
    /**
     * Method for delete the category
     * @param string
     */
    public function delete(Request $request){
        $request->validate([
            'target'  => 'required',
        ]);

        try{
            $category = BlogCategory::find($request->target);
            if($category) {
                
                $category->delete();
            }
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Category successfully deleted.']]);

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
            Validator::make($request->all(),$validation_rules)->validate();
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
