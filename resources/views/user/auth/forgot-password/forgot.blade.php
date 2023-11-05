
@extends('layouts.master')

@push('css')
    
@endpush

@section('content')
<section class="forgot-password pt-80 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-5">
                <div class="forgot-password-area">
                    <div class="account-wrapper">
                        <div class="account-logo text-center">
                            <a href="{{ setRoute('index') }}" class="site-logo">
                                <img src="{{ get_logo($basic_settings) }}" alt="logo">
                            </a>
                        </div>
                        <div class="forgot-password-content ptb-30">
                            <h3 class="title">{{ __("Reset Your Forgotten Password?") }}</h3>
                            <p>{{ __("Take control of your account by resetting your password. Our password recovery page guides you through the necessary steps to securely reset your password.") }}</p>
                        </div>
                        <form class="account-form" method="POST" action="{{  setRoute('user.password.forgot.send.code')  }}">
                            @csrf
                            <div class="row ml-b-20">
                                <div class="col-lg-12 form-group">
                                    <input type="text" required class="form-control form--control" name="credentials" placeholder="{{ __("Email") }}" spellcheck="false" data-ms-editor="true">
                                </div>
                                <div class="col-lg-12 form-group text-center">
                                    <button type="submit" class="btn--base btn w-100"> {{ __("Send OTP") }}</button>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <div class="account-item">
                                        <label>{{ __("Back To") }} <a href="{{ setRoute('index') }}" class="header-account-btn text--base">{{ __("Home Page") }}</a></label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
    
@endpush