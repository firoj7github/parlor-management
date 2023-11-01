<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Http\Helpers\Response;
use App\Models\Admin\ParlourList;
use App\Constants\NotificationConst;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Constants\SupportTicketConst;
use App\Models\Admin\AdminNotification;
use App\Models\ParlourBooking;
use App\Providers\Admin\BasicSettingsProvider;
use Pusher\PushNotifications\PushNotifications;

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

        $total_users     = (User::toBase()->count() == 0) ? 1 : User::toBase()->count();
        $unverified_user = User::toBase()->where('email_verified',0)->count();
        $active_user     = User::toBase()->where('status',true)->count();
        $banned_user     = User::toBase()->where('status',false)->count();
        $user_percent    = (($active_user * 100 ) / $total_users);

        if ($user_percent > 100) {
            $user_percent = 100;
        }

        $total_parlours     = (ParlourList::toBase()->count() == 0) ? 1 : ParlourList::toBase()->count();
        $active_parlour     = ParlourList::toBase()->where('status',true)->count();
        $pending_parlour    = ParlourList::toBase()->where('status',false)->count();
        $parlour_percent    = (($active_parlour * 100) / $total_parlours);
        
        if($parlour_percent > 100){
            $parlour_percent = 100;
        }


        $total_bookings     = (ParlourBooking::toBase()
                                ->whereNot('status',global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)
                                ->count() == 0) ? 1 : ParlourBooking::toBase()->count();
        $pending_booking    = ParlourBooking::toBase()->where('status',global_const()::PARLOUR_BOOKING_STATUS_PENDING) ->count();
        $confirm_booking    = ParlourBooking::toBase()->where('status',global_const()::PARLOUR_BOOKING_STATUS_CONFIRM_PAYMENT)->count();
        $booking_percent    = ((($pending_booking + $confirm_booking) * 100) / $total_bookings);
        
        if($booking_percent > 100){
            $booking_percent  = 100;
        }

        $total_money        = ParlourBooking::toBase()
                                ->whereNot('status',global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)
                                ->sum('price');
        $total_charges      = ParlourBooking::toBase()
                                ->whereNot('status',global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)
                                ->sum('total_charge');
        

        $total_ticket       = (SupportTicket::toBase()->count() == 0) ? 1 : SupportTicket::toBase()->count();
        $active_ticket      = SupportTicket::toBase()->where('status',SupportTicketConst::ACTIVE)->count();
        $pending_ticket     = SupportTicket::toBase()->where('status',SupportTicketConst::PENDING)->count();

        if($pending_ticket == 0 && $active_ticket != 0){
            $percent_ticket = 100;
        }elseif($pending_ticket == 0 && $active_ticket == 0){
            $percent_ticket = 0;
        }else{
            $percent_ticket = ($active_ticket / ($active_ticket + $pending_ticket)) * 100;
        }


        $data                       = [
            'unverified_user'       => $unverified_user,
            'active_user'           => $active_user,
            'user_percent'          => $user_percent,
            'total_user_count'      => User::all()->count(),

            'active_parlour'        => $active_parlour,
            'pending_parlour'       => $pending_parlour,
            'parlour_percent'       => $parlour_percent,
            'total_parlour_count'   => ParlourList::all()->count(),

            'pending_booking'        => $pending_booking,
            'confirm_booking'        => $confirm_booking,
            'booking_percent'        => $booking_percent,
            'total_booking_count'    => ParlourBooking::all()->count(),

            'total_money'           => $total_money,
            'total_charges'         => $total_charges,

            'active_ticket'         => $active_ticket,
            'pending_ticket'        => $pending_ticket,
            'percent_ticket'        => $percent_ticket,
            'total_ticket_count'    => SupportTicket::all()->count(),

            
        ];

        return view('admin.sections.dashboard.index',compact(
            'page_title',
            'data',
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
