<?php

namespace App\Http\Controllers\Api\V1\User\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\UserAuthorization;
use App\Traits\User\LoggedInUsers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Notifications\User\Auth\SendAuthorizationCode;

class LoginController extends Controller
{
        /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    protected $request_data;

    use AuthenticatesUsers, LoggedInUsers;

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|max:40',
            'password' => 'required|min:6',
        ]);

        if($validator->fails()){
            $error = ['error' => $validator->errors()->all()];
            return Response::error($error,[],400);
        }

        $user = User::where('username', trim(strtolower($request->email)))->orWhere('email', $request->email)->first();

        if(!$user){
            $error =['The credentials does not match'];
            return Response::error($error,[],400);
        }

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $user_data = [
            'token' => $token,
            'image_path' => get_files_public_path('user-profile'),
            'default_image' => get_files_public_path('default'),
            'user' => new UserResource($user)
        ];

        if(Hash::check($request->password, $user->password)){
            if($user->status == 0){
                $error = ['error'=>['Account Has been Suspended']];
                return Response::validation($error);
            }elseif($user->email_verified == 0){
                $user_authorize = UserAuthorization::where("user_id",$user->id)->first();
                $resend_code = generate_random_code();
                $user_authorize->update([
                    'code'          => $resend_code,
                    'created_at'    => now(),
                ]);
                $data = $user_authorize->toArray();
                $user->notify(new SendAuthorizationCode((object) $data));
                $message = ['success' => ['Please check email and verify your account']];
                return Response::success($message, $user_data);
            }

            $this->refreshUserWallets($user);
            $this->createLoginLog($user);

            $message = ['Login Successfull'];
            return Response::success($message,$user_data);
        }else{
            $error = ['The credentials does not match'];
            return Response::error($error,[],400);
        }
    }


    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $request->merge(['status' => true]);
        $request->merge([$this->username() => $request->credentials]);
        return $request->only($this->username(), 'password','status');
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        $request = $this->request_data->all();
        $credentials = $request['credentials'];
        if(filter_var($credentials,FILTER_VALIDATE_EMAIL)) {
            return "email";
        }
        return "username";
    }


    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard("api");
    }


    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user, $token)
    {
        $user->update([
            'two_factor_verified'   => false,
        ]);
        try{
            $this->refreshUserWallets($user);
        }catch(Exception $e) {
            return Response::error(['Login Failed! Failed to refresh wallet! Please try again'],[],500);
        }
        $this->createLoginLog($user);

        return Response::success(['User successfully logged in.'],[
            'token'         => $token,
            'user_info'     => $user->only([
                'id',
                'firstname',
                'lastname',
                'fullname',
                'username',
                'email',
                'mobile_code',
                'mobile',
                'full_mobile',
                'email_verified',
                'kyc_verified',
                'two_factor_verified',
                'two_factor_status',
                'two_factor_secret',
            ]),
        ],200);
    }
}
