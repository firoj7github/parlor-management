<?php

namespace App\Http\Controllers\Api\V1\User;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use Illuminate\Support\Carbon;
use App\Models\DoctorAppointment;
use App\Http\Controllers\Controller;
use App\Models\HomeTestService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\Admin\CurrencyProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Providers\Admin\BasicSettingsProvider;

class ProfileController extends Controller
{
    public function profileInfo() {
        $user = auth()->guard("api")->user();

        $response_data = $user->only([
            'id',
            'firstname',
            'lastname',
            'username',
            'email',
            'mobile_code',
            'mobile',
            'image',
            'kyc_verified',
            'date_of_birth'
        ]);
        if($response_data['date_of_birth'] != null){
            $response_data['date_of_birth']  = Carbon::parse($user->date_of_birth)->toDateTimeString();
        }
        
        $response_data['country']        = $user->address->country ?? "";
        $response_data['city']           = $user->address->city ?? "";
        $response_data['state']          = $user->address->state ?? "";
        $response_data['zip']    = $user->address->zip ?? "";
        $response_data['address']        = $user->address->address ?? "";
        $response_data['kyc']            = [
            'data'          => $user->kyc->data ?? [],
            'reject_reason' => $user->kyc->reject_reason ?? "", 
        ];

        $image_paths = [
            'base_url'          => url("/"),
            'path_location'     => files_asset_path_basename("user-profile"),
            'default_image'     => files_asset_path_basename("profile-default"),
        ];

        $instructions = [
            'kyc_verified'      => "0: Default, 1: Approved, 2: Pending, 3:Rejected",
        ];

        return Response::success(['Profile info fetch successfully!'],[
            'instructions'  => $instructions,
            'user_info'     => $response_data , 
            'image_paths'   => $image_paths,
            'countries'     => get_all_countries(['id','name','mobile_code']),
        ],200);
    }

    public function profileInfoUpdate(Request $request) {
        $validator = Validator::make($request->all(),[
            'firstname'     => "required|string|max:60",
            'lastname'      => "required|string|max:60",
            'country'       => "required|string|max:50",
            'mobile_code'   => "required|string|max:20",
            'mobile'        => "required|string|max:20",
            'gender'        => "nullable|string|max:20",
            'date_of_birth' => "nullable|string|date",
            'state'         => "nullable|alpha_num|max:50",
            'city'          => "nullable|alpha_num|max:50",
            'zip'      => "nullable|numeric",
            'address'       => "nullable|string|max:250",
            'image'         => "nullable|image|mimes:jpg,png,svg,webp|max:10240",
        ]);

        if($validator->fails()) return Response::error($validator->errors()->all(),[]);

        $validated = $validator->validate();
        $validated['mobile']        = get_only_numeric_data($validated['mobile']);
        $validated['mobile_code']   = get_only_numeric_data($validated['mobile_code']);
        $complete_phone             = $validated['mobile_code'] . $validated['mobile'];
        $validated['full_mobile']   = $complete_phone;

        $user = auth()->guard(get_auth_guard())->user();

        if(User::whereNot('id',$user->id)->where("full_mobile",$validated['full_mobile'])->exists()) {
            return Response::error(['Phone number already exists'],[],400);
        }
        if(is_numeric($validated['city'])){
            return Response::error(['The City must only contain letters.'],[],400);
        }
        if(is_numeric($validated['state'])){
            return Response::error(['The State must only contain letters.'],[],400);
        }
        $validated['address']       = [
            'country'       =>$validated['country'],
            'state'         => $validated['state'] ?? "", 
            'city'          => $validated['city'] ?? "", 
            'zip'   => $validated['zip'] ?? "", 
            'address'       => $validated['address'] ?? "",
        ];

        if($request->hasFile("image")) {
            $image = upload_file($validated['image'],'junk-files',$user->image);
            $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'user-profile');
            delete_file($image['dev_path']);
            chmod(get_files_path('user-profile') . '/' . $upload_image, 0644);
            $validated['image']     = $upload_image;
        }

        try{
            $user->update($validated);
        }catch(Exception $e) {
            return Response::error(["Something went wrong! Please try again"],[],500);
        }

        return Response::success(['Profile successfully updated!'],[],200);
    }

    public function profilePasswordUpdate(Request $request) {
        $basic_settings = BasicSettingsProvider::get();
        $password_rule = "required|string|min:6|confirmed";
        if($basic_settings->secure_password) {
            $password_rule = ["required",Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(),"confirmed"];
        }

        $validator = Validator::make($request->all(),[
            'current_password'      => "required|string",
            'password'              => $password_rule,
        ]);

        if($validator->fails()) return Response::error($validator->errors()->all(),[]);
        $validated = $validator->validate();

        if(!Hash::check($validated['current_password'],auth()->guard("api")->user()->password)) {
            return Response::error(['Current password didn\'t match'],[],400);
        }

        try{
            auth()->guard("api")->user()->update([
                'password'  => Hash::make($validated['password']),
            ]);
        }catch(Exception $e) {  
            return Response::error(['Something went wrong! Please try again.'],[],500);
        }

        return Response::success(['Password successfully updated!'],[],200);
    }

    public function logout(Request $request) {
        
        $user = Auth::guard(get_auth_guard())->user();
        // dd($user);
        $token = $user->token();
        try{
            $token->revoke();
        }catch(Exception $e) {
            return Response::error(['Something went wrong! Please try again'],[],500);
        }
        return Response::success(['Logout success!'],[],200);
    }

    //user history

    public function history(){
        $booking    = DoctorAppointment::with(['doctors','schedules','user'])->where('user_id',auth()->user()->id)->get()->map(function($data){
            $originalDate = $data->created_at;
            $formattedDate = Carbon::parse($originalDate);
            
            $date  = $formattedDate->format('d');    
            $month = $formattedDate->format('F');  
            $year  = $formattedDate->format('Y');  
            return [
                'id'              => $data->id,
                'doctor_name'     => $data->doctors->name,
                'patient_name'    => $data->name,
                'patient_mobile'  => $data->phone,
                'patient_email'   => $data->email,
                'type'            => $data->type,
                'fees'            => get_amount($data->doctors->fees).' '.CurrencyProvider::default()->code,
                'day'             => $data->schedules->week->day,
                'from_time'       => $data->schedules->from_time,
                'to_time'         => $data->schedules->to_time,
                'status'          => $data->status,
                'date'            => $date,
                'month'           => $month,
                'year'            => $year,
            ]; 
        });
        
        return Response::success(['History data fetch successfully.'],$booking,200);
    }
    //user history

    public function homeServiceHistory(){
        
        $home_service_booking    = HomeTestService::where('user_id',auth()->user()->id)->get()->map(function($data){
            $originalDate = $data->created_at;
            $formattedDate = Carbon::parse($originalDate);
            
            $date  = $formattedDate->format('d');    
            $month = $formattedDate->format('F');  
            $year  = $formattedDate->format('Y');  
            return [
                'id'              => $data->id,
                'slug'            => $data->slug,
                'patient_name'    => $data->name,
                'patient_email'   => $data->email,
                'patient_mobile'  => $data->phone,
                'type'            => $data->type,
                'schedule'        => $data->schedule,
                'status'          => $data->status,
                'date'            => $date,
                'month'           => $month,
                'year'            => $year,
            ]; 
        });
        return Response::success(['History data fetch successfully.'],$home_service_booking,200);
    }

}
