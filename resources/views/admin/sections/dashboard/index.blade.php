@extends('admin.layouts.master')

@push('css')

@endpush

@section('page-title')
    @include('admin.components.page-title',['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("admin.dashboard"),
        ]
    ], 'active' => __("Dashboard")])
@endsection

@section('content')
    <div class="dashboard-area">
        <div class="dashboard-item-area">
            <div class="row">
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Users") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ formatNumberInKNotation($data['total_user_count']) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Active") }} {{ $data['active_user'] }}</span>
                                    <span class="badge badge--info">{{ __("Unverified") }} {{ $data['unverified_user'] }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart6" data-percent="{{ $data['user_percent'] }}"><span>{{ round($data['user_percent']) }}%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ ("Total Parlours") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ formatNumberInKNotation($data['total_parlour_count']) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">{{ __("Active") }} {{ $data['active_parlour'] }}</span>
                                    <span class="badge badge--warning">{{ __("Pending") }} {{ $data['pending_parlour'] }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart7" data-percent="{{ $data['parlour_percent'] }}"><span>{{ round($data['parlour_percent']) }}%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ ("Total Bookings") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ formatNumberInKNotation($data['total_booking_count']) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">{{ __("Confirm") }} {{ $data['confirm_booking'] }}</span>
                                    <span class="badge badge--warning">{{ __("Pending") }} {{ $data['pending_booking'] }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart8" data-percent="{{ $data['booking_percent'] }}"><span>{{ round($data['booking_percent']) }}%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Support Ticket") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ formatNumberInkNotation($data['total_ticket_count']) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">{{ __("Active") }} {{ formatNumberInkNotation($data['active_ticket']) }}</span>
                                    <span class="badge badge--warning">{{ __("Pending") }} {{ formatNumberInkNotation($data['pending_ticket']) }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart10" data-percent="{{ $data['percent_ticket'] }}"><span>{{ round($data['percent_ticket']) }}%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ ("Total Booking Money") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ get_default_currency_symbol() }}{{ get_amount($data['total_money']) }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ ("Total Fees & Charges") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ get_default_currency_symbol() }}{{ get_amount($data['total_charges']) }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="chart-area mt-15">
        <div class="row mb-15-none">
            <div class="col-xxl-6 col-xl-6 col-lg-6 mb-15">
                <div class="chart-wrapper">
                    <div class="chart-area-header">
                        <h5 class="title">Monthly Add Money Chart</h5>
                    </div>
                    <div class="chart-container">
                        <div id="chart1" class="sales-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-xl-6 col-lg-6 mb-15">
                <div class="chart-wrapper">
                    <div class="chart-area-header">
                        <h5 class="title">Revenue Chart</h5>
                    </div>
                    <div class="chart-container">
                        <div id="chart2" class="revenue-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-xl-6 col-lg-6 mb-15">
                <div class="chart-wrapper">
                    <div class="chart-area-header">
                        <h5 class="title">Add Money Analytics</h5>
                    </div>
                    <div class="chart-container">
                        <div id="chart3" class="order-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-6 col-xxl-3 col-xl-6 col-lg-6 mb-15">
                <div class="chart-wrapper">
                    <div class="chart-area-header">
                        <h5 class="title">User Analytics</h5>
                    </div>
                    <div class="chart-container">
                        <div id="chart4" class="balance-chart"></div>
                    </div>
                    <div class="chart-area-footer">
                        <div class="chart-btn">
                            <a href="javascript:void(0)" class="btn--base w-100">Send Report</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-12 col-xxl-3 col-xl-12 col-lg-12 mb-15">
                <div class="chart-wrapper">
                    <div class="chart-area-header">
                        <h5 class="title">Growth</h5>
                    </div>
                    <div class="chart-container">
                        <div id="chart5" class="growth-chart"></div>
                    </div>
                    <div class="chart-area-footer">
                        <div class="chart-btn">
                            <a href="javascript:void(0)" class="btn--base w-100">Send Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-area mt-15">
        <div class="table-wrapper">
            <div class="table-header">
                <h5 class="title">Latest Transactions</h5>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Phone</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Time</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <ul class="user-list">
                                    <li><img src="assets/images/user/user-1.jpg" alt="user"></li>
                                </ul>
                            </td>
                            <td><span>Sean Black</span></td>
                            <td>sean@gmail.com</td>
                            <td>sean</td>
                            <td>123-456(008)90</td>
                            <td>5.00</td>
                            <td><span class="text--info">Stripe</span></td>
                            <td><span class="badge badge--warning">Pending</span></td>
                            <td>2022-05-30 03:46 PM</td>
                            <td>
                                <button type="button" class="btn btn--base bg--success"><i class="las la-check-circle"></i></button>
                                <button type="button" class="btn btn--base bg--danger"><i class="las la-times-circle"></i></button>
                                <a href="add-logs-edit.html" class="btn btn--base"><i class="las la-expand"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <ul class="user-list">
                                    <li><img src="assets/images/user/user-2.jpg" alt="user"></li>
                                </ul>
                            </td>
                            <td><span>Merri Diamond</span></td>
                            <td>merri@gmail.com</td>
                            <td>merri</td>
                            <td>123-456(008)90</td>
                            <td>5.00</td>
                            <td><span class="text--info">Paypal</span></td>
                            <td><span class="badge badge--success">Completed</span></td>
                            <td>2022-05-30 03:46 PM</td>
                            <td>
                                <button type="button" class="btn btn--base bg--success"><i class="las la-check-circle"></i></button>
                                <button type="button" class="btn btn--base bg--danger"><i class="las la-times-circle"></i></button>
                                <a href="add-logs-edit.html" class="btn btn--base"><i class="las la-expand"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <ul class="user-list">
                                    <li><img src="assets/images/user/user-3.jpg" alt="user"></li>
                                </ul>
                            </td>
                            <td><span>Sean Black</span></td>
                            <td>sean@gmail.com</td>
                            <td>sean</td>
                            <td>123-456(008)90</td>
                            <td>5.00</td>
                            <td><span class="text--info">Razorpay</span></td>
                            <td><span class="badge badge--danger">Canceled</span></td>
                            <td>2022-05-30 03:46 PM</td>
                            <td>
                                <button type="button" class="btn btn--base bg--success"><i class="las la-check-circle"></i></button>
                                <button type="button" class="btn btn--base bg--danger"><i class="las la-times-circle"></i></button>
                                <a href="add-logs-edit.html" class="btn btn--base"><i class="las la-expand"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
        
@endpush