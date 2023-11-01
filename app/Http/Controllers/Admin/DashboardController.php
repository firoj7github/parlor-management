<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin\Blog;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Http\Helpers\Response;
use App\Models\ParlourBooking;
use App\Models\Admin\ParlourList;
use App\Models\Admin\BlogCategory;
use App\Constants\NotificationConst;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Constants\SupportTicketConst;
use App\Models\Admin\AdminNotification;
use App\Providers\Admin\BasicSettingsProvider;
use Pusher\PushNotifications\PushNotifications;

class DashboardController extends Controller
{
    public function getAllMonthNames(){
        $monthNames = collect([]);

        for ($monthNumber = 1; $monthNumber <= 12; $monthNumber++) {
            $monthName = Carbon::createFromDate(null, $monthNumber, null)->format('M');
            $monthNames->push($monthName);
        }

        return $monthNames;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title         = "Dashboard";
        $last_month_start   =  date('Y-m-01', strtotime('-1 month', strtotime(date('Y-m-d'))));
        $last_month_end     =  date('Y-m-31', strtotime('-1 month', strtotime(date('Y-m-d'))));
        $this_month_start   = date('Y-m-01');
        $this_month_end     = date('Y-m-d');

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
        
        $this_month_money = ParlourBooking::toBase()
                            ->whereNot('status',global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)
                            ->whereBetween('created_at', [$this_month_start, $this_month_end])
                            ->sum('price');

        $last_month_money = ParlourBooking::toBase()
                            ->whereNot('status',global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)
                            ->whereBetween('created_at', [$last_month_start, $last_month_end])
                            ->sum('price');
        $this_month_charge = ParlourBooking::toBase()
                            ->whereNot('status',global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)
                            ->whereBetween('created_at', [$this_month_start, $this_month_end])
                            ->sum('total_charge');

        $last_month_charge = ParlourBooking::toBase()
                            ->whereNot('status',global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)
                            ->whereBetween('created_at', [$last_month_start, $last_month_end])
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
        $user_chart = [$active_user, $banned_user,$unverified_user,$total_users];
        $start = strtotime(date('Y-m-01'));
        $end = strtotime(date('Y-m-31'));


        $pending_data  = [];
        $complete_data  = [];
        $month_day  = [];

        while ($start <= $end) {
            $start_date = date('Y-m-d', $start);
            
            
            $pending = ParlourBooking::where('status',global_const()::PARLOUR_BOOKING_STATUS_PENDING)
                                        ->whereDate('created_at',$start_date)
                                        ->count();
            $complete = ParlourBooking::where('status',global_const()::PARLOUR_BOOKING_STATUS_CONFIRM_PAYMENT)
                                        ->whereDate('created_at',$start_date)
                                        ->count();
            
            $pending_data[]  = $pending;
            $complete_data[]  = $complete;
            $month_day[] = date('Y-m-d', $start);
            $start = strtotime('+1 day',$start);
        }
        // Chart one
        $chart_one_data = [
            'pending_data'  => $pending_data,
            'complete_data'  => $complete_data,
            
        ];
        $booking_data               = ParlourBooking::
                                        whereNot('status',global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)
                                        ->latest()->take(3)->get();

        $total_categories           = (BlogCategory::toBase()->count() == 0) ? 1 : BlogCategory::toBase()->count();
        $active_category            = BlogCategory::toBase()->where('status',true)->count();
        $inactive_category          = BlogCategory::toBase()->where('status',false)->count();
        $category_percent           = (($active_category * 100) / $total_categories);
        
        if($category_percent > 100){
            $category_percent = 100;
        }

        $total_blogs           = (Blog::toBase()->count() == 0) ? 1 : Blog::toBase()->count();
        $active_blog            = Blog::toBase()->where('status',true)->count();
        $inactive_blog          = Blog::toBase()->where('status',false)->count();
        $blog_percent           = (($active_blog * 100) / $total_blogs);
        
        if($blog_percent > 100){
            $blog_percent = 100;
        }

        $data                       = [
            'unverified_user'       => $unverified_user,
            'active_user'           => $active_user,
            'user_percent'          => $user_percent,
            'total_user_count'      => User::all()->count(),
            'user_chart_data'       => $user_chart,

            'active_parlour'        => $active_parlour,
            'pending_parlour'       => $pending_parlour,
            'parlour_percent'       => $parlour_percent,
            'total_parlour_count'   => ParlourList::all()->count(),

            'pending_booking'        => $pending_booking,
            'confirm_booking'        => $confirm_booking,
            'booking_percent'        => $booking_percent,
            'total_booking_count'    => ParlourBooking::all()->count(),
            'chart_one_data'         => $chart_one_data,
            'month_day'              => $month_day,
            'total_money'           => $total_money,
            'total_charges'         => $total_charges,
            'this_month_money'      => $this_month_money,
            'last_month_money'      => $last_month_money,
            'this_month_charge'     => $this_month_charge,
            'last_month_charge'     => $last_month_charge,

            'active_ticket'         => $active_ticket,
            'pending_ticket'        => $pending_ticket,
            'percent_ticket'        => $percent_ticket,
            'total_ticket_count'    => SupportTicket::all()->count(),

            'active_category'        => $active_category,
            'inactive_category'      => $inactive_category,
            'category_percent'       => $category_percent,
            'total_category_count'   => BlogCategory::all()->count(),

            'active_blog'            => $active_blog,
            'inactive_blog'          => $inactive_blog,
            'blog_percent'           => $blog_percent,
            'total_blog_count'       => Blog::all()->count(),
            
        ];
        $months = $this->getAllMonthNames();

        return view('admin.sections.dashboard.index',compact(
            'page_title',
            'data',
            'months',
            'booking_data',
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
