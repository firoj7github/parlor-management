<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;

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
                'site_logo_dark'    => $data->site_logo_dark,
                'site_logo'         => $data->site_logo,
                'site_fav_dark'     => $data->site_fav_dark,
                'site_fav'          => $data->site_fav,
                'created_at'        => $data->created_at,
            ];
        });
        $basic_image_path   = [
            'base_url'      => url('/'),
            'path_location' => files_asset_path_basename('image-assets'),
            'default_image' => files_asset_path_basename('default')
        ];
        return Response::success(['Basic Settings Data Fetch Successfully.'],[
            'basic_settings'    => $basic_settings,
            'basic_image_path'  => $basic_image_path,
        ],200);
    }
}
