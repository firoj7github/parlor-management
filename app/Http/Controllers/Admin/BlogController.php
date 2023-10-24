<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Admin\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\Language;
use App\Constants\LanguageConst;
use App\Models\Admin\BlogCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function create(){
        $page_title = "New Blog Create ";
        $category   = BlogCategory::where('status',true)->get();
        $languages  = Language::get();

        return view('admin.sections.setup-sections.blog.create',compact(
            'page_title',
            'category',
            'languages'
        ));
    }
    public function store(Request $request) {
        $basic_field_name = [
            'title'         => "required|string|max:255",
            'description'   => "required|string|max:5000000",
            'tags'          => "required|array",
        ];

        $validator = Validator::make($request->all(),[
            'image'         => "nullable",
            'category'      => 'required',
        ]);
        
        $validated          = $validator->validate();
        $data['language']   = $this->contentValidate($request,$basic_field_name);

        // make slug
        $not_removable_lang = LanguageConst::NOT_REMOVABLE;
        $slug_text          = $data['language'][$not_removable_lang]['title'] ?? "";
        if($slug_text == "") {
            $slug_text = $data['language'][get_default_language_code()]['title'] ?? "";
            if($slug_text == "") {
                $slug_text = Str::uuid();
            }
        }
        $slug = Str::slug(Str::lower($slug_text));

        if(Blog::where('slug',$slug)->exists()) return back()->with(['error' => ['Blog title is similar. Please update/change this title']]);

        $data['image'] = null;
        if($request->hasFile("image")) {
            $data['image']  = $this->imageValidate($request,"image",null);
        }
      
        try{
            $update_value = [
                'slug' => $slug,
                'category_id' => $validated['category'],
                'data'=> $data
            ];
            Blog::updateOrCreate($update_value);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong. Please try again']]);
        }
        

        return redirect()->route('admin.setup.sections.section','blog')->with(['success' => ['Blog created successfully!']]);
    }

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
            $blog = Blog::find($validated['data_target']);
            if($blog) {
                $blog->update([
                    'status'    => ($validated['status'] == true) ? false : true,
                ]);
            }
        } catch (Exception $e) {
            $error = ['error' => ['Something went wrong!. Please try again.']];
            return Response::error($error, null, 500);
        }

        $success = ['success' => ['Blog status updated successfully!']];
        return Response::success($success, null, 200);
    }
    public function edit($slug){
        $blog           = Blog::where('slug',$slug)->first();
        if(!$blog) return back()->with(['error' => ['Blog Does not exists']]);
        $page_title     = "Blog Edit Page";
        $category       = BlogCategory::where('status',true)->get();
        $languages      = Language::get();

        return view('admin.sections.setup-sections.blog.edit',compact(
            'blog',
            'page_title',
            'category',
            'languages'
        ));
    }
    public function update(Request $request,$slug){
        $blog            = Blog::where('slug',$slug)->first();
        $basic_field_name   = [
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'tags'          => 'required|array'
        ];
        $validator = Validator::make($request->all(),[
            'category'      => 'required',
        ]);
        
        $validated          = $validator->validate();
        $data['language']   = $this->contentValidate($request,$basic_field_name);
        $request->merge(['old_image' => $blog->data->image ?? null]);

        if($request->hasFile("image")){
            $data['image']  = $this->imageValidate($request,"image",$blog->data->image ?? null);
        }else {
            $data['image']  = $blog->data->image ?? null;
        }
        try{
            $update_value = [
                'category_id'   => $validated['category'],
                'data'          => $data
            ];
            $blog->update($update_value);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }
        return redirect()->route('admin.setup.sections.section','blog')->with(['success' => ['Blog Data Updated Successfully.']]);
    }
    public function delete(Request $request){
        
        $request->validate([
            'target'    => "required|string"
        ]);

        try{
            $blog = Blog::find($request->target);
            if($blog) {
                $image_name = $blog->data?->image ?? null;
                if($image_name) {
                    $image_link = get_files_path('site-section') . "/" . $image_name;
                    delete_file($image_link);
                }
                $blog->delete();
            }
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong. Please try again']]);
        }
        return back()->with(['success' => ['Blog deleted successfully!']]);
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

    /**
     * Method for validate request image if have
     * @param object $request
     * @param string $input_name
     * @param string $old_image
     * @return boolean|string $upload
     */
    public function imageValidate($request,$input_name,$old_image = null) {
        if($request->hasFile($input_name)) {
            $image_validated = Validator::make($request->only($input_name),[
                $input_name         => "image|mimes:png,jpg,webp,jpeg,svg",
            ])->validate();

            $image = get_files_from_fileholder($request,$input_name);
            $upload = upload_files_from_path_dynamic($image,'site-section',$old_image);
            return $upload;
        }

        return false;
    }
}
