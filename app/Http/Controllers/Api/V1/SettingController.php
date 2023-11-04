<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Admin\Area;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Models\UserNotification;
use App\Models\Admin\AppSettings;
use App\Models\Admin\ParlourList;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;
use App\Models\Admin\AppOnboardScreens;
use App\Models\Admin\ParlourHasService;
use App\Providers\Admin\CurrencyProvider;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\ParlourListHasSchedule;

class SettingController extends Controller
{
    /**
     * Method for get the basic settings data 
     */
    public function basicSettings(){
        $basic_settings     = BasicSettings::orderBy("id")->get()->map(function($data){
            return [
                'id'                => $data->id,
                'site_name'         => $data->site_name,
                'base_color'        => $data->base_color,
                'secondary_color'   => $data->secondary_color,
                'site_logo_dark'    => $data->site_logo_dark,
                'site_logo'         => $data->site_logo,
                'site_fav_dark'     => $data->site_fav_dark,
                'site_fav'          => $data->site_fav,
                'created_at'        => $data->created_at,
            ];
        });
        $default_currency     = Currency::where('default',true)->orderBy("id")->get()->map(function($data){
            return [
                'id'                => $data->id,
                'country'           => $data->country,
                'name'              => $data->name,
                'code'              => $data->code,
                'symbol'            => $data->symbol,
            ];
        });
        // splash screen

        $splash_screen   = AppSettings::orderBy("id")->get()->map(function($data){

            return [
                'id'                          => $data->id,
                'version'                     => $data->version,
                'splash_screen_image'         => $data->splash_screen_image,
                'created_at'                  => $data->created_at,
            ];
        });

        // onboard screen

        $onboard_screen   = AppOnboardScreens::where('status',true)->orderBy("id")->get()->map(function($data){

            return [
                'id'                           => $data->id,
                'title'                        => $data->title,
                'sub_title'                    => $data->sub_title,
                'image'                        => $data->image,
                'status'                       => $data->status,
                'last_edit_by'                 => $data->last_edit_by,
                'created_at'                   => $data->created_at,

            ];
        });

        $basic_image_path   = [
            'base_url'      => url('/'),
            'path_location' => files_asset_path_basename('image-assets'),
            'default_image' => files_asset_path_basename('default')
        ];

        $screen_image_path    = [
            'base_url'                     => url("/"),
            'path_location'                => files_asset_path_basename("app-images"),
            'default_image'                => files_asset_path_basename("default"),
        ];

        return Response::success(['Basic Settings Data Fetch Successfully.'],[
            'default_currency'  => $default_currency,
            'basic_settings'    => $basic_settings,
            'splash_screen'     => $splash_screen,
            'onboard_screen'    => $onboard_screen,
            'basic_image_path'  => $basic_image_path,
            'app_image_path'    => $screen_image_path,
        ],200);
    }
    /**
     * Method for get user notification
     */
    public function notification(){
        $user           = auth()->user()->id;
        $notification   = UserNotification::where('user_id',$user)->orderBy("id")->get()->map(function($data){
            return [
                'id'            => $data->id,
                'message'       => $data->message,
            ]; 
        });
        return Response::success(['Notification Data Fetch Successfuly.'],[
            'notification'      => $notification,
        ],200);
    }
    /**
     * Method for get the parlour List
     */
    public function parlourList(){
        $areas          = Area::where('status',true)->orderBy("id")->get()->map(function($data){
            return [
                'id'        => $data->id,
                'slug'      => $data->slug,
                'name'      => $data->name,
                'status'    => $data->status,
            ];
        });

        $parlour_list   = ParlourList::where('status',true)->orderBy("id")->get()->map(function($data){
            return [
                'id'                => $data->id,
                'area_id'           => $data->area_id,
                'slug'              => $data->slug,
                'name'              => $data->name,
                'manager_name'      => $data->manager_name,
                'experience'        => $data->experience,
                'speciality'        => $data->speciality,
                'contact'           => $data->contact,
                'address'           => $data->address,
                'off_days'          => $data->off_days,
                'number_of_dates'   => $data->number_of_dates,
                'image'             => $data->image,
                'status'            => $data->status,
                'created_at'        => $data->created_at  
            ];
        });
        $parlour_image_path   = [
            'base_url'      => url('/'),
            'path_location' => files_asset_path_basename('site-section'),
            'default_image' => files_asset_path_basename('default')
        ];
        return Response::success(['Data Fetch Successfuly.'],[
            'area'                      => $areas,
            'parlour_list'              => $parlour_list,
            'parlour_image_path'        => $parlour_image_path,
        ],200);
    }
    /**
     * Method for parlour Service and schedule
     */
    public function scheduleService(){
        $parlour_has_service   = ParlourHasService::orderBy("id")->get()->map(function($data){
            return [
                'id'                => $data->id,
                'parlour_list_id'   => $data->parlour_list_id,
                'service_name'      => $data->service_name,
                'price'             => $data->price,
            ];
        });

        $parlour_has_schedule   = ParlourListHasSchedule::where('status',true)->orderBy("id")->get()->map(function($data){
            return [
                'id'                => $data->id,
                'parlour_list_id'   => $data->parlour_list_id,
                'from_time'         => $data->from_time,
                'to_time'           => $data->to_time,
                'max_client'        => $data->max_client,
                'status'            => $data->status
            ];
        });
        
        return Response::success(['Data Fetch Successfuly.'],[
            'parlour_has_service'       => $parlour_has_service,
            'parlour_has_schedule'      => $parlour_has_schedule,
        ],200);
    }
    /**
     * Method for search parlour
     */
    public function searchParlour(Request $request){
        
        $validator      = Validator::make($request->all(),[
            'area'          => 'nullable',
            'name'          => 'nullable',
        ]);
        if ($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }
        if($request->area && $request->name ){ 
            $parlour_lists    = ParlourList::where('area_id',$request->area)->where('name','like','%'.$request->name.'%')->get(); 
        }else if($request->area){
            $parlour_lists    = ParlourList::where('area_id',$request->area)->get();
        }else {
            $parlour_lists    = ParlourList::where('name','like','%'.$request->name.'%')->get();
        }
        if ($parlour_lists->isEmpty()) {
            return Response::error(['Parlour not found!'],[],404);
        }
        return Response::success(['Parlour Find Successfully!'],$parlour_lists,200);
    }
    /**
     * Method for get all country list
     */
    public function countryList(){
        return Response::success(['Parlour Find Successfully!'],[
            'countries'     => get_all_countries(['id','name','mobile_code']),
        ],200);
    }
}
