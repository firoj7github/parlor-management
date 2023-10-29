
<!-- favicon -->
<link rel="shortcut icon" href="{{ get_fav($basic_settings) ?? "" }}" type="image/x-icon">
{{-- google font link --}}
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<!-- fontawesome css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/fontawesome-all.min.css') }}">
<!-- bootstrap css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/bootstrap.min.css') }}">
<!-- swipper css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/swiper.min.css') }}">
<!-- lightcase css links -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/lightcase.css') }}">
<!-- line-awesome-icon css -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/line-awesome.min.css') }}">
<!-- animate.css -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/animate.css') }}">
 <!-- AOS css link -->
 <link rel="stylesheet" href="{{ asset('public/frontend/css/aos.css') }}">
 <!-- nice-select -->
 <link rel="stylesheet" href="{{ asset('public/frontend/css/nice-select.css') }}">
 <!-- Popup  -->
 <link rel="stylesheet" href="{{ asset('public/backend/library/popup/magnific-popup.css') }}">
<!-- odometer css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/odometer.css') }}">
 <!-- select2 css -->
 <link rel="stylesheet" href="{{ asset('public/frontend/css/select2.min.css') }}">
<!-- main style css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/style.css') }}">
<!-- file holder css -->
<link rel="stylesheet" href="https://cdn.appdevs.net/fileholder/v1.0/css/fileholder-style.css" type="text/css">

@php
    $base_color = $basic_settings->base_color;
    $secondary_color = $basic_settings->secondary_color;
@endphp
<style>
    :root {
        --primary-color: {{ $base_color }};
    }
    :root {
        --secondary-color: {{ $secondary_color }};
    }
</style>