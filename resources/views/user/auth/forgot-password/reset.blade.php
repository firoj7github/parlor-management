<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $page_title ?? $basic_settings->site_name }}</title>
    @include('partials.header-asset')
    @stack('css')
</head>

<body>
    
    @include('frontend.partials.preloader')

    <div id="body-overlay" class="body-overlay"></div>

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start acount  
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div class="new-password pt-80 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-5">
                <div class="new-password-area">
                    <div class="account-wrapper">
                        <span class="account-cross-btn"></span>
                        <div class="account-logo text-center">
                            <a href="{{ setRoute('index') }}" class="site-logo">
                                <img src="{{ get_logo($basic_settings) }}" alt="logo">
                            </a>
                        </div>
                        <form class="account-form ptb-30" method="POST" action="{{ setRoute('user.password.reset',$token) }}">
                            @csrf
                            <div class="row ml-b-20">
                                <label>{{ __("Enter New Password") }}</label>
                                <div class="col-lg-12 form-group show_hide_password">
                                    <input type="password" name="password" class="form-control form--control"  placeholder="{{ __("New Password") }}">
                                    <a href="javascript:void(0)" class="show-pass"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                                <label>{{ __("Enter Confirm Password") }}</label>
                                <div class="col-lg-12 form-group show_hide_password-2">    
                                    <input type="password" name="password_confirmation" class="form-control form--control"  placeholder="{{ __("Confirm Password") }}">
                                    <a href="javascript:void(0)" class="show-pass"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                                <div class="col-lg-12 form-group text-center pt-3">
                                    <button type="submit" class="btn--base w-100">{{ __("Confirm") }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End acount  
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

@include('partials.footer-asset')
@include('admin.partials.notify')
<script>
    $(document).ready(function() {
        $("#show_hide_password a").on('click', function(event) {
            event.preventDefault();
            if($('#show_hide_password input').attr("type") == "text"){
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass( "fa-eye-slash" );
                $('#show_hide_password i').removeClass( "fa-eye" );
            }else if($('#show_hide_password input').attr("type") == "password"){
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass( "fa-eye-slash" );
                $('#show_hide_password i').addClass( "fa-eye" );
            }
        });
    });
</script>

</body>