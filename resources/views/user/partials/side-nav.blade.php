<div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-inner-wrapper">
            <div class="sidebar-logo">
                <a href="{{ setRoute('index') }}" class="sidebar-main-logo">
                    <img src="{{ get_logo($basic_settings) }}" alt="logo">
                </a>
                <button class="sidebar-menu-bar">
                    <i class="fas fa-exchange-alt"></i>
                </button>
            </div>
            <div class="sidebar-menu-wrapper">
                <ul class="sidebar-menu">
                    <li class="sidebar-menu-item">
                        <a href="{{ setRoute('user.profile.index') }}">
                            <i class="menu-icon las la-palette"></i>
                            <span class="menu-title">{{ __("Profile") }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="{{ setRoute('user.my.booking.index') }}">
                            <i class="menu-icon las la-cart-plus"></i>
                            <span class="menu-title">{{ __("My Booking") }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="javascript:void(0)" class="logout-btn">
                            <i class="menu-icon fas fa-sign-out-alt"></i>
                            <span class="menu-title">{{__("Logout")}}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="sidebar-doc-box bg-overlay-base bg_img" data-background="{{ asset('public/frontend') }}/images/element/sidebar.jpg">
            <div class="sidebar-doc-icon">
                <i class="las la-headphones-alt"></i>
            </div>
            <div class="sidebar-doc-content">
                <h4 class="title">{{ __("Help Center") }}</h4>
                <p>{{ __("How can we help you?") }}</p>
                <div class="sidebar-doc-btn">
                    <a href="{{ setRoute('user.support.ticket.index') }}" class="btn--base w-100">{{ __("Get Support") }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
<script>
    $(".logout-btn").click(function(){
        var actionRoute =  "{{ setRoute('user.logout') }}";
        var target      = 1;
        var message     = `{{ __("Are you sure to") }} <strong>{{ __("Logout") }}</strong>?`;
  
        openAlertModal(actionRoute,target,message,"Logout","POST");
    });
  </script>
@endpush