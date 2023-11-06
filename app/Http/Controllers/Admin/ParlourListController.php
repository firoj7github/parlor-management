<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Admin\Area;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use Illuminate\Support\Carbon;
use App\Models\Admin\ParlourList;
use App\Http\Controllers\Controller;
use App\Models\Admin\ParlourHasService;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\ParlourListHasSchedule;
use Illuminate\Validation\ValidationException;

class ParlourListController extends Controller
{
    /**
     * Method for show parlour list page
     * @return view
     */
    public function index(){
        $page_title     = "Parlour List";
        $parlour_lists  = ParlourList::orderBYDESC('id')->get();

        return view('admin.sections.parlour-list.index',compact(
            'page_title',
            'parlour_lists'
        ));
    }
    /**
     * Method for show doctor-care create page
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
    */
    public function create(){
        $page_title      = "Parlour Create";
        $areas           = Area::where('status',true)->get();
        $todayDate       = Carbon::now()->format('d F, Y');

        return view('admin.sections.parlour-list.create',compact(
            'page_title',
            'areas',
            'todayDate'
        ));
    }
    public function getScheduleDays(){

        return view('admin.components.parlour-list.schedule-item');
    }
    public function getService(){

        return view('admin.components.parlour-list.service-item');
    }
    /**
     * Method for store parlour list information
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'area'             => 'required|integer',
            'name'             => 'required|string|max:50',
            'manager_name'     => 'nullable|string',
            'experience'       => 'required|string|max:100',
            'speciality'       => 'nullable',
            'contact'          => 'required',
            'address'          => 'nullable',
            'off_days'         => 'required|string',
            'number_of_dates'  => 'required|integer',
            'service_name'     => 'required|array',
            'service_name.*'   => 'required|string',
            'price'            => 'required|array',
            'price.*'          => 'required|string',
            'from_time'        => 'required|array',
            'from_time.*'      => 'required|string',
            'to_time'          => 'required|array',
            'to_time.*'        => 'required|string',
            'max_client'       => 'required|array',
            'max_client.*'     => 'required|integer',
            'image'            => 'nullable',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }

        $validated          = $validator->validate();
        $validated['slug']  = Str::uuid();
        $validated['area_id']        = $validated['area'];
        if(ParlourList::where('contact',$validated['contact'])->exists()){
            throw ValidationException::withMessages([
                'name'  => "Parlour already exists!",
            ]);
        }

        if($request->hasFile("image")){
            $validated['image'] = $this->imageValidate($request,"image",null);
        }
        $service_name   = $validated['service_name'];
        $price          = $validated['price'];
        $from_time      = $validated['from_time'];
        $to_time        = $validated['to_time'];
        $max_client     = $validated['max_client'];
        $validated      = Arr::except($validated,['service_name','price','from_time','to_time','max_client','area']);
        try{
            $parlour_list = ParlourList::create($validated);
            if(count($from_time) > 0){
                $days_shedule = [];
                foreach($from_time as $key => $day_id){
                    $days_shedule[] = [
                        'parlour_list_id'   => $parlour_list->id,
                        'from_time'         => $from_time[$key],
                        'to_time'           => $to_time[$key],
                        'max_client'        => $max_client[$key],
                        'created_at'        => now(),
                    ];
                }
                ParlourListHasSchedule::insert($days_shedule);
            }
            if(count($service_name) > 0){
                $services = [];
                foreach($service_name as $key => $day_id){
                    $services[] = [
                        'parlour_list_id'   => $parlour_list->id,
                        'service_name'      => $service_name[$key],
                        'price'             => $price[$key],
                        'created_at'        => now(),
                    ];
                }
                ParlourHasService::insert($services);
            }
        }catch(Exception $e){
            return back()->with(['error' => ["Something went wrong.Please try again."]]);
        }
        return redirect()->route('admin.parlour.list.index')->with(['success' => ["Parlour Created Successfully!"]]);
    }
    /**
     * Method for show the edit parlour list page
     * @param $slug
     * @param \Illuminate\Http\Request $request
     */
    public function edit($slug){
        $page_title             = "Parlour Edit";
        $parlour_list           = ParlourList::where('slug',$slug)->first();
        if(!$parlour_list) return back()->with(['error' =>  ['Parlour List Not Exists!']]);
        $areas                  = Area::where('status',true)->get();
        $parlour_has_schedule   = ParlourListHasSchedule::where('parlour_list_id',$parlour_list->id)->get();
        $parlour_has_service    = ParlourHasService::where('parlour_list_id',$parlour_list->id)->get();

        return view('admin.sections.parlour-list.edit',compact(
            'page_title',
            'parlour_list',
            'areas',
            'parlour_has_schedule',
            'parlour_has_service'
        ));
    }
    /**
     * Method for update the parlour list information
     * @param $slug
     * @param \Illuminate\Http\Request $request
     */
    public function update(Request $request,$slug){
        $parlour_list   = ParlourList::where('slug',$slug)->first();
        $validator = Validator::make($request->all(),[
            'area'             => 'required', 
            'name'             => 'required|string|max:50',
            'manager_name'     => 'nullable|string',
            'experience'       => 'required|string|max:100',
            'speciality'       => 'nullable',
            'contact'          => 'required',
            'address'          => 'nullable',
            'off_days'         => 'required|string',
            'number_of_dates'  => 'required|integer',
            'service_name'     => 'required|array',
            'service_name.*'   => 'required|string',
            'price'            => 'required|array',
            'price.*'          => 'required|string',
            'from_time'        => 'required|array',
            'from_time.*'      => 'required|string',
            'to_time'          => 'required|array',
            'to_time.*'        => 'required|string',
            'max_client'       => 'required|array',
            'max_client.*'     => 'required|integer',
            'image'            => 'nullable',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }
        $validated             = $validator->validate();
        $validated['slug']     = Str::uuid();
        $validated['area_id']      = $validated['area'];
        if(ParlourList::whereNot('id',$parlour_list->id)->where('contact',$validated['contact'])->exists()){
            throw ValidationException::withMessages([
                'name' => "Parlour already exists!",
            ]);
        }
        if($request->hasFile('image')){
            $validated['image']  =  $this->imageValidate($request,"image",null);
        }
        $service_name       = $validated['service_name'];
        $price              = $validated['price'];
        $from_time          = $validated['from_time'];
        $to_time            = $validated['to_time'];
        $max_client         = $validated['max_client'];
        $validated          = Arr::except($validated,['service_name','price','from_time','to_time','max_client','area']);
        try{
            $parlour_schedule_ids = $parlour_list->schedules->pluck('id');
            ParlourListHasSchedule::whereIn('id',$parlour_schedule_ids)->delete();
            $parlour_service_ids = $parlour_list->services->pluck('id');
            ParlourHasService::whereIn('id',$parlour_service_ids)->delete();

            $parlour_list->update($validated);
            if(count($from_time) > 0){
                $time_schedule = [];
                foreach($from_time as $key => $day_id){
                    $time_schedule[]  = [
                        'parlour_list_id'   => $parlour_list->id,
                        'from_time'         => $from_time[$key],
                        'to_time'           => $to_time[$key],
                        'max_client'        => $max_client[$key],
                        'created_at'        => now(),
                    ];
                }
                ParlourListHasSchedule::insert($time_schedule);
            }
            if(count($service_name) > 0){
                $services = [];
                foreach($service_name as $key => $day_id){
                    $services[]  = [
                        'parlour_list_id'   => $parlour_list->id,
                        'service_name'      => $service_name[$key],
                        'price'             => $price[$key],
                        'created_at'        => now(),
                    ];
                }
                ParlourHasService::insert($services);
            }

        }catch(Exception $e){
            return back()->with(['error'  => ['Something went wrong! Please try again.']]);
        }
        return redirect()->route('admin.parlour.list.index')->with(['success' => ['Parlour Updated Successfully!']]);
    }
    /**
     * Method for update doctor status
     * @param \Illuminate\Http\Request  $request
     */
    public function statusUpdate(Request $request){

        $validator = Validator::make($request->all(),[
            'data_target'  => 'required|numeric|exists:parlour_lists,id',
            'status'       => 'required|boolean',
        ]);

        if($validator->fails()){
            $errors = ['error' => $validator->errors() ];
            return Response::error($errors);
        }

        $validated = $validator->validate();
        $doctors = ParlourList::find($validated['data_target']);

        try {
            $doctors->update([
                'status'   => ($validated['status']) ? false: true,
            ]);
        } catch (Exception $e) {
            $errors = ['error' => ['Something went wrong! Please try again.'] ];
            return Response::error($errors,null,500);
        }
        $success = ['success' => ['Parlour list status updated successfully!']];
        return Response::success($success);
    }
    /**
     * Method for delete the parlour list information
     * @param \Illuminate\Http\Request $request
     */
    public function delete(Request $request){
        $validator = Validator::make($request->all(),[
            'target'    => 'required|numeric',
        ]);

        $parlour_list   = ParlourList::find($request->target);
        try{
            $parlour_list->delete();
        }catch(Exception $e){
            return back()->with(['error'    =>  ['Something went wrong. Please try again!']]);
        }
        return back()->with(['success'  =>  ['Parlour list deleted successfully.']]);

    }
    /**
     * Method for image validate
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
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
