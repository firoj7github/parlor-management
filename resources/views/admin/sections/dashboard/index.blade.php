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
                                <h6 class="title">Total Visitors</h6>
                                <div class="user-info">
                                    <h2 class="user-count">6258</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">Active 40</span>
                                    <span class="badge badge--info">New 22</span>
                                    <span class="badge badge--warning">Today 12</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart6" data-percent="65"><span>65%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">Add Money Balance</h6>
                                <div class="user-info">
                                    <h2 class="user-count">$865k</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">Total 40k</span>
                                    <span class="badge badge--warning">Pending 20K</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart7" data-percent="80"><span>80%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">Money Out Balance</h6>
                                <div class="user-info">
                                    <h2 class="user-count">$273</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">Total 40k</span>
                                    <span class="badge badge--warning">Pending 20K</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart8" data-percent="90"><span>90%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">Total Profit</h6>
                                <div class="user-info">
                                    <h2 class="user-count">$650</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">This Day 40k</span>
                                    <span class="badge badge--warning">This Month 20K</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart9" data-percent="70"><span>70%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">User Active Tickets</h6>
                                <div class="user-info">
                                    <h2 class="user-count">630</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--warning">Pending 45</span>
                                    <span class="badge badge--success">Solved 25</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart10" data-percent="50"><span>50%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">Total Users</h6>
                                <div class="user-info">
                                    <h2 class="user-count">1190</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">Active 45</span>
                                    <span class="badge badge--warning">Unverified 25</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart11" data-percent="85"><span>85%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">Pending Add Money</h6>
                                <div class="user-info">
                                    <h2 class="user-count">$865k</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">This Day 40k</span>
                                    <span class="badge badge--warning">This Month 20K</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart12" data-percent="60"><span>60%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">Pending Money Out</h6>
                                <div class="user-info">
                                    <h2 class="user-count">$273</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">This Day 40k</span>
                                    <span class="badge badge--warning">This Month 20K</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart13" data-percent="75"><span>75%</span></div>
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