<nav class="navbar-wrapper">
    <div class="dashboard-title-part">
        <div class="left">
            <div class="icon">
                <button class="sidebar-menu-bar">
                    <i class="fas fa-exchange-alt"></i>
                </button>
            </div>
            <div class="dashboard-path">
                @yield('breadcrumb')
            </div>
        </div>
        <div class="right">
            <div class="header-notification-wrapper">
                <button class="notification-icon">
                    <i class="las la-bell"></i>
                </button>
                <div class="notification-wrapper">
                    <div class="notification-header">
                        <h5 class="title">Notification</h5>
                    </div>
                    <ul class="notification-list">
                        <li>
                            <div class="thumb">
                                <img src="{{ asset('public/frontend') }}/images/client/client-1.webp" alt="user">
                            </div>
                            <div class="content">
                                <div class="title-area">
                                    <h5 class="title">Appointment</h5>
                                    <span class="time">Thu 3.00PM</span>
                                </div>
                                <span class="sub-title">Evolve Salon Appointment booking Done. Schedule Wednesday 5th</span>
                            </div>
                        </li>
                        <li>
                            <div class="thumb">
                                <img src="{{ asset('public/frontend') }}/images/client/client-2.webp" alt="user">
                            </div>
                            <div class="content">
                                <div class="title-area">
                                    <h5 class="title">Appointment</h5>
                                    <span class="time">Thu 3.00PM</span>
                                </div>
                                <span class="sub-title">Evolve Salon Appointment booking Done. Schedule Wednesday 5th</span>
                            </div>
                        </li>
                        <li>
                            <div class="thumb">
                                <img src="{{ asset('public/frontend') }}/images/client/client-3.webp" alt="user">
                            </div>
                            <div class="content">
                                <div class="title-area">
                                    <h5 class="title">Appointment</h5>
                                    <span class="time">Thu 3.00PM</span>
                                </div>
                                <span class="sub-title">Evolve Salon Appointment booking Done. Schedule Wednesday 5th</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="header-user-wrapper">
                <div class="header-user-thumb">
                    <a href="{{ setRoute('user.profile.index') }}"><img src="{{  auth()->user()->userImage ?? '' }}" alt="client"></a>
                </div>
            </div>
        </div>
    </div>
</nav>