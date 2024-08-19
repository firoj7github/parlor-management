<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __("Admin | Login") }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <!-- fontawesome css link -->
    <link rel="stylesheet" href="{{ asset('backend/css/fontawesome-all.css') }}">
    <!-- bootstrap css link -->
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.css') }}">
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('backend/images/logo/favicon.png') }}" type="image/x-icon">
    <!-- line-awesome-icon css -->
    <link rel="stylesheet" href="{{ asset('backend/css/line-awesome.css') }}">
    <!-- animate.css -->
    <link rel="stylesheet" href="{{ asset('backend/css/animate.css') }}">
    <!-- nice select css -->
    <link rel="stylesheet" href="{{ asset('backend/css/nice-select.css') }}">
    <!-- select2 css -->
    <link rel="stylesheet" href="{{ asset('backend/css/select2.css') }}">
    <!-- rte css -->
    <link rel="stylesheet" href="{{ asset('backend/css/rte_theme_default.css') }}">
    <!-- main style css link -->
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">

    @stack('css')
</head>
<body>

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Admin
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div class="page-wrapper">
    <div class="account-area">
        @yield('section')
    </div>
    <ul class="bg-bubbles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Admin
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

<!-- jquery -->
<script src="{{ asset('backend/js/jquery-3.6.0.js') }}"></script>
<!-- bootstrap js -->
<script src="{{ asset('backend/js/bootstrap.bundle.js') }}"></script>
<!-- smooth scroll js -->
<script src="{{ asset('backend/js/smoothscroll.js') }}"></script>
<!-- nice select js -->
<script src="{{ asset('backend/js/jquery.nice-select.js') }}"></script>
<!-- select2 js -->
<script src="{{ asset('backend/js/select2.js') }}"></script>
<!-- rte js -->
<script src="{{ asset('backend/js/rte.js') }}"></script>
<!-- rte plugins js -->
<script src='{{ asset('backend/js/all_plugins.js') }}'></script>
<!-- main -->
<script src="{{ asset('backend/js/main.js') }}"></script>

@include('admin.partials.notify')

@stack('script')



</body>
</html>
