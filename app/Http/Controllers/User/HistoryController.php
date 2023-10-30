<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParlourBooking;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Method for view the users history page
     */
    public function index(){
        $page_title     = "| History";
        $data           = ParlourBooking::auth()->with(['parlour','schedule','payment_gateway','user'])->whereNot('status',global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)->orderBYDESC('id')->paginate(10);

        return view('user.sections.history.index',compact(
            'page_title',
            'data',
        ));
    }
}
