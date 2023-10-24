<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Providers\Admin\BasicSettingsProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\PushNotifications\PushNotifications;
use App\Models\Admin\AdminNotification;
use App\Constants\NotificationConst;
use App\Http\Helpers\Response;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "Dashboard";
        return view('admin.sections.dashboard.index',compact(
            'page_title',
        ));
    }


    /**
     * Logout Admin From Dashboard
     * @return view
     */
    public function logout(Request $request) {

        $push_notification_setting = BasicSettingsProvider::get()->push_notification_config;

        if($push_notification_setting) {
            $method = $push_notification_setting->method ?? false;

            if($method == "pusher") {
                $instant_id     = $push_notification_setting->instance_id ?? false;
                $primary_key    = $push_notification_setting->primary_key ?? false;

                if($instant_id && $primary_key) {
                    $pusher_instance = new PushNotifications([
                        "instanceId"    => $instant_id,
                        "secretKey"     => $primary_key,
                    ]);

                    $pusher_instance->deleteUser("".Auth::user()->id."");
                }
            }

        }

        $admin = auth()->user();
        try{
            $admin->update([
                'last_logged_out'   => now(),
                'login_status'      => false,
            ]);
        }catch(Exception $e) {
            // Handle Error
        }

        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }


    /**
     * Function for clear admin notification
     */
    public function notificationsClear() {
        $admin = auth()->user();

        if(!$admin) {
            return false;
        }

        try{
            $admin->update([
                'notification_clear_at'     => now(),
            ]);
        }catch(Exception $e) {
            $error = ['error' => ['Something went wrong! Please try again.']];
            return Response::error($error,null,404);
        }

        $success = ['success' => ['Notifications clear successfully!']];
        return Response::success($success,null,200);
    }
}
