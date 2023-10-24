<div class="account-section">
    <div class="account-bg"></div>
    <div class="account-area change-form">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="account-section-img">
                    <img src="{{ asset('public/frontend/images/element/about-img.jpg') }}" alt="img">
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
            <div class="account-close"></div>
                <div class="account-form-area">
                    <h3 class="title">{{ __("Log in and Stay Connected") }}</h3>
                    <p>{{ __("Our secure login process ensures the confidentiality of your information. Log in today and stay connected to your finances, anytime and anywhere.") }}</p>
                    <form class="account-form" method="POST" action="{{ setRoute('user.login.submit') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 form-group">
                                <input type="email" class="form-control form--control" name="credentials" placeholder="{{ __("Enter Email") }}...">
                            </div>
                            <div class="col-lg-12 form-group show_hide_password">
                                <input type="password" class="form-control form--control" name="password" placeholder="{{ __("Enter Password") }}...">
                                <a href="#0" class="show-pass"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                            </div>
                            <div class="col-lg-12 form-group">
                                <div class="forgot-item text-end">
                                    <label><a href="{{ setRoute('user.password.forgot') }}" class="text--base">{{ __("Forgot Password?") }}</a></label>
                                </div>
                            </div>
                            <div class="col-lg-12 form-group text-center">
                                <button type="submit" class="btn--base w-100">{{ __("Login Now") }}</button>
                            </div>
                            
                            <div class="col-lg-12 text-center">
                                <div class="account-item">
                                    <label>{{ __("Don't Have An Account?") }} <a href="#0" class="account-control-btn">{{ __("Register Now") }}</a></label>
                                </div>
                            </div>
                            <div class="col-lg-12 text-center">
                                <div class="terms-item">
                                    <label>{{ __("By clicking Login you are agreeing with our") }} <a href="javascript:void(0)">{{ __("Terms of feature") }}</a></label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="account-area">
        <div class="account-close"></div>
        <div class="row register-form-input">
            <div class="col-lg-6 col-md-6">
                <div class="account-section-img">
                    <img src="{{ asset('public/frontend/images/element/about-img.jpg') }}" alt="img">
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="account-form-area">
                    <h3 class="title">{{ __("Register for an Account Today") }}</h3>
                    <p>{{ __("Become a part of our community by registering for an account today. Enjoy a range of benefits and features tailored to meet your needs. Our registration page makes it easy to create your account, providing a seamless and user-friendly experience.") }}</p>
                    <form class="account-form" method="POST" action="{{ setRoute('user.register.submit') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-md-6 form-group">
                                <input type="text" class="form-control form--control" name="firstname" placeholder="{{ __("First Name") }}">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <input type="text" class="form-control form--control" name="lastname" placeholder="{{ __("Last Name") }}">
                            </div>
                            <div class="col-lg-12 form-group">
                                <input type="email" class="form-control form--control" name="email" placeholder="{{ __("Email") }}">
                            </div>
                            <div class="col-lg-12 form-group">
                                <select class="form--control select2-auto-tokenize country-select" data-placeholder="Select Country" data-old="{{ old('country') }}" name="country"></select>
                            </div>
                            <div class="col-lg-12 form-group show_hide_password">
                                <input type="password" class="form-control form--control" name="password" placeholder="{{ __("Password") }}">
                                <a href="#0" class="show-pass"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                            </div>
                            <div class="col-lg-12 form-group">
                                <div class="custom-check-group">
                                    <input type="checkbox" id="level-1" name="agree">
                                    <label for="level-1">{{ __("I have agreed with") }} <a href="#0">{{ __("Terms Of Use & Privacy Policy") }}</a></label>
                                </div>
                            </div>
                            <div class="col-lg-12 form-group text-center">
                                <button type="submit" class="btn--base w-100">{{ __("Register Now") }}</button>
                            </div>
                            <div class="col-lg-12 text-center">
                                <div class="account-item">
                                    <label>{{ __("Already Have An Account?") }} <a href="#0" class="account-control-btn">{{ __("Login Now") }}</a></label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
<script>
    getAllCountries("{{ setRoute('global.countries') }}",$(".country-select"));
        $(document).ready(function(){

            $(".country-select").select2();

            $("select[name=country]").change(function(){
                var phoneCode = $("select[name=country] :selected").attr("data-mobile-code");
                placePhoneCode(phoneCode);
            });

            setTimeout(() => {
                var phoneCodeOnload = $("select[name=country] :selected").attr("data-mobile-code");
                placePhoneCode(phoneCodeOnload);
            }, 400);
        });
</script>
@endpush