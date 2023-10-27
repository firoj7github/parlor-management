<?php

namespace App\Http\Controllers\Api\V1\User\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use Illuminate\Support\Carbon;
use App\Models\UserPasswordReset;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Providers\Admin\BasicSettingsProvider;
use App\Notifications\User\Auth\PasswordResetEmail;

class ForgotPasswordController extends Controller
{
    public function findUserSendCode(Request $request) {
        $validator = Validator::make($request->all(),[
            'credentials'       => "required|string|max:50",
        ]);

        if($validator->fails()) return Response::error($validator->errors()->all(),[]);

        $validated = $validator->validate();

        // Find User
        $column = "username";
        if(check_email($validated['credentials'])) {
            $column = "email";
        }
        $user = User::where($column,$validated['credentials'])->first();
        if(!$user) return Response::error(['User doesn\'t exists'],[],404);
        if($user->status != GlobalConst::ACTIVE) return Response::error(['Your account is temporary banded. Please contact with system admin'],[],400);

        // send mail to user to verify email
        try{
            $token = generate_unique_string("user_password_resets","token",80);
            $code = generate_random_code();

            UserPasswordReset::where("user_id",$user->id)->delete();
            $password_reset = UserPasswordReset::create([
                'user_id'       => $user->id,
                'token'         => $token,
                'code'          => $code,
            ]);
            
            $user->notify(new PasswordResetEmail($user,$password_reset));
        }catch(Exception $e) {
            return Response::error(['Something went wrong! Please try again'],[],500);
        }

        return Response::success(['Verification code sended to your email address.'],['token' => $token,'wait_time' => ""],200);
    }

    public function verifyCode(Request $request) {
        $validator = Validator::make($request->all(),[
            'token'         => "required|string|exists:user_password_resets,token",
            'code'          => "required|numeric|exists:user_password_resets,code",
        ]);

        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }
        
        $validated = $validator->validate();

        $basic_settings = BasicSettingsProvider::get();
        $otp_exp_seconds = $basic_settings->otp_exp_seconds ?? 0;

        $password_reset = UserPasswordReset::where("token",$validated['token'])->first();

        if(Carbon::now() >= $password_reset->created_at->addSeconds($otp_exp_seconds)) {
            foreach(UserPasswordReset::get() as $item) {
                if(Carbon::now() >= $item->created_at->addSeconds($otp_exp_seconds)) {
                    $item->delete();
                }
            }
            return Response::error(['Session expired. Please try again.'],[],440);
        }

        if($password_reset->code != $validated['code']) {
            return Response::error(['Verification Otp is Invalid'],[],400);
        }

        // Success
        return Response::success(['OTP successfully verified!'],['token' => $validated['token']],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resendCode(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'token'     => "required|string|exists:user_password_resets,token"
        ]);

        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }
        $validated = $validator->validate();
        $password_reset = UserPasswordReset::where('token',$validated['token'])->first();

        if(!$password_reset) return Response::error(['Request token is invalid'],[],400);

        if(Carbon::now() <= $password_reset->created_at->addMinutes(GlobalConst::USER_PASS_RESEND_TIME_MINUTE)) {
            return Response::error(['You can resend verification code after '.Carbon::now()->diffInSeconds($password_reset->created_at->addMinutes(GlobalConst::USER_PASS_RESEND_TIME_MINUTE)). ' seconds'],[],400);
        }

        DB::beginTransaction();
        try{
            $update_data = [
                'code'          => generate_random_code(),
                'created_at'    => now(),
                'token'         => $validated['token'],
            ];
            DB::table('user_password_resets')->where('token',$validated['token'])->update($update_data);
            $password_reset->user->notify(new PasswordResetEmail($password_reset->user,(object) $update_data));
            DB::commit();
        }catch(Exception $e) {
            DB::rollback();
            return Response::error(['Something went wrong! Please try again'],[],500);
        }

        return Response::success(['OTP resend success'],['token' => $validated['token']],200);
    }

    public function resetPassword(Request $request) {

        $basic_settings = BasicSettingsProvider::get();
        $password_rule = "required|string|min:6|confirmed";
        if($basic_settings->secure_password) {
            $password_rule = ["required",Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(),"confirmed"];
        }

        $validator = Validator::make($request->all(),[
            'token'         => "required|string|exists:user_password_resets,token",
            'password'      => $password_rule,
        ]);
        
        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }
        $validated = $validator->validate();

        $password_reset = UserPasswordReset::where("token",$validated['token'])->first();
        if(!$password_reset) return Response::error(['Request token is invalid'],[],400);

        try{
            $password_reset->user->update([
                'password'      => Hash::make($validated['password']),
            ]);
            $password_reset->delete();
        }catch(Exception $e) {
            return Response::error(['Something went wrong! Please try again'],[],500);
        }

        return Response::success(['Password reset success'],[],200);
    }

}
