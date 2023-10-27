<?php

namespace App\Http\Controllers\Api\V1\User\Auth;

use Exception;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\UserAuthorization;
use App\Traits\User\LoggedInUsers;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Providers\Admin\BasicSettingsProvider;
use App\Traits\User\RegisteredUsers;

use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Notifications\User\Auth\SendAuthorizationCode;

class RegisterController extends Controller
{
    use LoggedInUsers, RegisteredUsers;

    protected $basic_settings;

    public function __construct()
    {
        $this->basic_settings = BasicSettingsProvider::get();
    }
    
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request){
        $basic_settings = $this->basic_settings;
        $passowrd_rule = "required|string|min:6";

        if($basic_settings->secure_password) {
            $passowrd_rule = ["required",Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()];
        }

        $agree_policy = $this->basic_settings->agree_policy == 1 ? 'required|in:on' : 'nullable';

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'     => 'required|email|max:160|unique:users',
            'password'  => $passowrd_rule,
            'policy'     => $agree_policy,
        ]);

        if($validator->fails()){
            $error =  ['error'=>$validator->errors()->all()];
            return Response::validation($error);
        }

        $validated = $validator->validated();

        //User Create

        $validated = Arr::except($validated,['agree']);

        $validated['firstname']      = $validated['first_name'];
        $validated['lastname']       = $validated['last_name'];
        $validated['email_verified'] = ($basic_settings->email_verification == true) ? 0 : 1;
        $validated['kyc_verified']   = 0;
        $validated['sms_verified']   = 0;
        $validated['status']         = 1;
        $validated['password']       = Hash::make($validated['password']);
        $validated['username']       = make_username($validated['first_name'],$validated['last_name']);

        $user = User::create($validated);

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $this->createUserWallets($user);

        if ($basic_settings->email_verification == true) {
            $data = [
                'user_id'       => $user->id,
                'code'          => generate_random_code(),
                'token'         => generate_unique_string("user_authorizations","token",200),
                'created_at'    => now(),
            ];
            DB::beginTransaction();
            try{
                UserAuthorization::where("user_id",$user->id)->delete();
                DB::table("user_authorizations")->insert($data);
                $user->notify(new SendAuthorizationCode((object) $data));
                DB::commit();
            }catch(Exception $e) {
                DB::rollBack();
                $error = ['Something went worng! Please try again'];
                return Response::error($error);
            }
        }

        if ($basic_settings->email_verification == 1 && $basic_settings->email_notification == 1) {
            $message =  ['Please check email and verify your account'];
        } else {
            $message =  ['Registration successful'];
        }

        $data = [
            'token' => $token,
            'image_path' => get_files_public_path('user-profile'),
            'default_image' => get_files_public_path('default'),
            'user' => new UserResource($user)
        ];
        return Response::success($message, $data);
    }
}
