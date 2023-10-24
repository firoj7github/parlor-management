<!DOCTYPE html>
<html lang="{{ get_default_language_code() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $basic_settings->site_name }} {{ $page_title ?? '' }}</title>
    @include('partials.header-asset')
    @stack('css')
</head>
<body>

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start body overlay
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="body-overlay" class="body-overlay"></div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End body overlay
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@include('frontend.partials.preloader')

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Dashboard
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div class="page-wrapper" >
    @include('user.partials.side-nav')
    <div class="main-wrapper">
        <div class="main-body-wrapper">
            @include('user.partials.top-nav')
            @yield('content')
        </div>
    </div>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Dashboard
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

@include('partials.footer-asset')
@include('admin.partials.notify')
@stack('script')
 

</body>
</html>