<!DOCTYPE html>
<html lang="{{ get_default_language_code() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $current_url = URL::current();
    @endphp

    @if($current_url == setRoute('index'))
        <title>{{$basic_settings->site_name ?? ''}}  - {{ $basic_settings->site_title ?? "" }}</title>
    @else
        <title>{{$basic_settings->site_name ?? ''}}  {{ $page_title ?? '' }}</title>
    @endif
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


@include('frontend.partials.header')

@include('user.auth.login-register')


@yield('content')


@include('frontend.partials.footer')
@include('frontend.partials.scroll-to-top')


<!-- jquery -->

@include('partials.footer-asset')
@include('admin.partials.notify')
@include('frontend.partials.extensions.tawk-to')
@stack('script')

@error('credentials')
    <script>
        openLoginModal();
    </script>
@enderror


@php
     $errorName ='';
@endphp
@if($errors->any())
@php
    $error = (object)$errors;
    $msg = $error->default;
    $messageNames  = $msg->keys();
    $errorName = $messageNames[0];
@endphp
@endif
<script>
    var error = "{{  $errorName }}";
    if(error == 'credentials' ){
        $('.account-section').addClass('active');
    }
    if(
        error == 'firstname' ||
        error == 'agree' ||
        error == 'register_password' ||
        error == 'register_email' ||
        error == 'lastname'
    ){
        $('.account-section').addClass('active');
        $('.account-area').toggleClass('change-form');
    }

</script>
</body>
</html>