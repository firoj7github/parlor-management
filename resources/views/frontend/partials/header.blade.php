@php
    $menues = DB::table('setup_pages')->where('status', 1)->get();
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Header
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<header class="header-section">
    <div class="header">
        <div class="header-bottom-area">
            <div class="container custom-container">
                <div class="header-menu-content">
                    <nav class="navbar navbar-expand-lg p-0">
                        <a class="site-logo site-title" href="{{ setRoute('index') }}"><img src="{{ get_logo($basic_settings) }}" alt="site-logo"></a>
                        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fas fa-bars"></span>
                        </button>
                        @php
                            $current_url = URL::current();
                        @endphp
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav main-menu ms-auto">
                                @foreach ($menues as $item)
                                    @php
                                        $title = $item->title ?? "";
                                    @endphp
                                    <li><a href="{{ url($item->url) }}" class=" @if($current_url == url($item->url)) active @endif ">{{ __($title) }} <i class="fas fa-caret-right"></i></a></li>
                                @endforeach
                            </ul>
                            <div class="header-action">
                                @auth
                                    <a class="btn--base" href="{{ setRoute('user.profile.index') }}">{{ __("Dashboard") }}</a>
                                @else
                                    <button class="btn--base header-account-btn">{{ __("Login Now") }}</button>
                                @endauth
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Header
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
