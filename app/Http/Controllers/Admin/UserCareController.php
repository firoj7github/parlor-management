<?php

namespace App\Http\Controllers\Admin;

use App\Constants\GlobalConst;
use Exception;
use App\Models\User;
use App\Models\UserLoginLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserMailLog;
use App\Notifications\User\SendMail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\Response;

class UserCareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "All Users";
        $users = User::orderBy('id', 'desc')->paginate(12);
        return view('admin.sections.user-care.index', compact(
            'page_title',
            'users'
        ));
    }

    /**
     * Display Active Users
     * @return view
     */
    public function active()
    {
        $page_title = "Active Users";
        $users = User::active()->orderBy('id', 'desc')->paginate(12);
        return view('admin.sections.user-care.index', compact(
            'page_title',
            'users'
        ));
    }


    /**
     * Display Banned Users
     * @return view
     */
    public function banned()
    {
        $page_title = "Banned Users";
        $users = User::banned()->orderBy('id', 'desc')->paginate(12);
        return view('admin.sections.user-care.index', compact(
            'page_title',
            'users',
        ));
    }

    /**
     * Display Email Unverified Users
     * @return view
     */
    public function emailUnverified()
    {
        $page_title = "Email Unverified Users";
        $users = User::active()->orderBy('id', 'desc')->emailUnverified()->paginate(12);
        return view('admin.sections.user-care.index', compact(
            'page_title',
            'users'
        ));
    }

    /**
     * Display SMS Unverified Users
     * @return view
     */
    public function SmsUnverified()
    {
        $page_title = "SMS Unverified Users";
        return view('admin.sections.user-care.index', compact(
            'page_title',
        ));
    }

    /**
     * Display KYC Unverified Users
     * @return view
     */
    public function KycUnverified()
    {
        $page_title = "KYC Unverified Users";
        $users = User::kycUnverified()->orderBy('id', 'desc')->paginate(8);
        return view('admin.sections.user-care.index', compact(
            'page_title',
            'users'
        ));
    }

    /**
     * Display Send Email to All Users View
     * @return view
     */
    public function emailAllUsers()
    {
        $page_title = "Email To Users";
        return view('admin.sections.user-care.email-to-users', compact(
            'page_title',
        ));
    }

    /**
     * Display Specific User Information
     * @return view
     */
    public function userDetails($username)
    {
        $page_title = "User Details";
        $user = User::where('username', $username)->first();
        if(!$user) return back()->with(['error' => ['Opps! User not exists']]);
        return view('admin.sections.user-care.details', compact(
            'page_title',
            'user',
        ));
    }

    public function sendMailUsers(Request $request) {
        $request->validate([
            'user_type'     => "required|string|max:30",
            'subject'       => "required|string|max:250",
            'message'       => "required|string|max:2000",
        ]);

        $users = [];
        switch($request->user_type) {
            case "active";
                $users = User::active()->get();
                break;
            case "all";
                $users = User::get();
                break;
            case "email_verified";
                $users = User::emailVerified()->get();
                break;
            case "kyc_verified";
                $users = User::kycVerified()->get();
                break;
            case "banned";
                $users = User::banned()->get();
                break;
        }

        try{
            Notification::send($users,new SendMail((object) $request->all()));
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Email successfully sended']]);

    }

    public function sendMail(Request $request, $username)
    {
        $request->merge(['username' => $username]);
        $validator = Validator::make($request->all(),[
            'subject'       => 'required|string|max:200',
            'message'       => 'required|string|max:2000',
            'username'      => 'required|string|exists:users,username',
        ]);
        if($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with("modal","email-send");
        }
        $validated = $validator->validate();
        $user = User::where("username",$username)->first();
        $validated['user_id'] = $user->id;
        $validated = Arr::except($validated,['username']);
        $validated['method']   = "SMTP";
        try{
            UserMailLog::create($validated);
            $user->notify(new SendMail((object) $validated));
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }
        return back()->with(['success' => ['Mail successfully sended']]);
    }

    public function userDetailsUpdate(Request $request, $username)
    {
        $request->merge(['username' => $username]);
        $validator = Validator::make($request->all(),[
            'username'              => "required|exists:users,username",
            'firstname'             => "required|string|max:60",
            'lastname'              => "required|string|max:60",
            'mobile'                => "nullable|string|max:20",
            'address'               => "nullable|string|max:250",
            'country'               => "required|string|max:50",
            'state'                 => "nullable|string|max:50",
            'city'                  => "nullable|string|max:50",
            'zip_code'              => "nullable|numeric|max_digits:8",
            'email_verified'        => 'required|boolean',
            'two_factor_verified'   => 'nullable|boolean',
            'kyc_verified'          => 'nullable|boolean',
            'status'                => 'required|boolean',
        ]);
        $validated = $validator->validate();
        $validated['address']  = [
            'country'       => $validated['country'] ?? "",
            'state'         => $validated['state'] ?? "",
            'city'          => $validated['city'] ?? "",
            'zip'           => $validated['zip_code'] ?? "",
            'address'       => $validated['address'] ?? "",
        ];
        $validated['mobile']            = remove_speacial_char($validated['mobile']);
        $validated['full_mobile']       = $validated['mobile'];

        $user = User::where('username', $username)->first();
        if(!$user) return back()->with(['error' => ['Opps! User not exists']]);

        try {
            $user->update($validated);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Profile Information Updated Successfully!']]);
    }

    public function loginLogs($username)
    {
        $page_title = "Login Logs";
        $user = User::where("username",$username)->first();
        if(!$user) return back()->with(['error' => ['Opps! User doesn\'t exists']]);
        $logs = UserLoginLog::where('user_id',$user->id)->paginate(12);
        return view('admin.sections.user-care.login-logs', compact(
            'logs',
            'page_title',
        ));
    }

    public function mailLogs($username) {
        $page_title = "User Email Logs";
        $user = User::where("username",$username)->first();
        if(!$user) return back()->with(['error' => ['Opps! User doesn\'t exists']]);
        $logs = UserMailLog::where("user_id",$user->id)->paginate(12);
        return view('admin.sections.user-care.mail-logs',compact(
            'page_title',
            'logs',
        ));
    }

    public function loginAsMember(Request $request,$username) {
        $request->merge(['username' => $username]);
        $request->validate([
            'target'            => 'required|string|exists:users,username',
            'username'          => 'required_without:target|string|exists:users',
        ]);

        try{
            $user = User::where("username",$request->username)->first();
            Auth::guard("web")->login($user);
        }catch(Exception $e) {
            return back()->with(['error' => [$e->getMessage()]]);
        }
        return redirect()->intended(route('user.profile.index'));
    }

    public function kycDetails($username) {
        $user = User::where("username",$username)->first();
        if(!$user) return back()->with(['error' => ['Opps! User doesn\'t exists']]);

        $page_title = "KYC Profile";
        return view('admin.sections.user-care.kyc-details',compact("page_title","user"));
    }

    public function kycApprove(Request $request, $username) {
        $request->merge(['username' => $username]);
        $request->validate([
            'target'        => "required|exists:users,username",
            'username'      => "required_without:target|exists:users,username",
        ]);
        $user = User::where('username',$request->target)->orWhere('username',$request->username)->first();
        if($user->kyc_verified == GlobalConst::VERIFIED) return back()->with(['warning' => ['User already KYC verified']]);
        if($user->kyc == null) return back()->with(['error' => ['User KYC information not found']]);

        try{
            $user->update([
                'kyc_verified'  => GlobalConst::APPROVED,
            ]);
        }catch(Exception $e) {
            $user->update([
                'kyc_verified'  => GlobalConst::PENDING,
            ]);
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }
        return back()->with(['success' => ['User KYC successfully approved']]);
    }

    public function kycReject(Request $request, $username) {
        $request->validate([
            'target'        => "required|exists:users,username",
            'reason'        => "required|string|max:500"
        ]);
        $user = User::where("username",$request->target)->first();
        if(!$user) return back()->with(['error' => ['User doesn\'t exists']]);
        if($user->kyc == null) return back()->with(['error' => ['User KYC information not found']]);

        try{
            $user->update([
                'kyc_verified'  => GlobalConst::REJECTED,
            ]);
            $user->kyc->update([
                'reject_reason' => $request->reason,
            ]);
        }catch(Exception $e) {
            $user->update([
                'kyc_verified'  => GlobalConst::PENDING,
            ]);
            $user->kyc->update([
                'reject_reason' => null,
            ]);

            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['User KYC information is rejected']]);
    }


    public function search(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);

        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        $users = User::search($validated['text'])->limit(10)->get();
        return view('admin.components.search.user-search',compact(
            'users',
        ));
    }
}
