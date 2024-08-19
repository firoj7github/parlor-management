@php
    $app_local      = get_default_language_code();
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Footer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<footer class="footer-section">
    <div class="container mx-auto">
        <div class="footer-content">
            <div class="row">
                <div class="col-xl-4 col-lg-5 mb-50">
                    <div class="footer-widget responcive-area">
                        <div class="footer-text">
                            <!-- <img src="{{ @$footer->value->footer->image ? get_image($footer->value->footer->image,'site-section') : get_logo($basic_settings) }}" alt="image"> -->
                            <img src="{{ asset('/frontend/images/icon/logo.webp') }}" alt="site-logo">
                            <p>{{ $footer->value->footer->language->$app_local->description ?? '' }}</p>
                        </div>
                        <div class="footer-social-icon">
                            <span>{{ __("Follow us :") }}</span>
                            @foreach ($footer->value->social_links ?? [] as $item)
                                <a href="{{ $item->link ?? '' }}"><i class="{{ $item->icon ?? '' }}"></i></a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-3 col-md-6 mb-30">
                    <div class="footer-widget responcive-area">
                        <div class="footer-widget-heading">
                            <h3 class="title">{{ __("Useful Links") }}</h3>
                        </div>
                        <ul>
                            @if(isset($usefull_links))
                                @foreach ($usefull_links as $item)
                                    <li><a href="{{ setRoute('link',$item->slug)}}">{{ $item->title->language?->$app_local?->title ?? "" }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-3 col-md-6 mb-50">
                    <div class="footer-widget responcive-area-2">
                        <ul class="footer-list">
                            <h3 class="title">{{ __("Quick Contact") }}</h3>
                            <li><a href="javascript:void(0)">Dhaka, Bangladesh</a></li>
                            <li><a href="tel:{{ $contact?->value?->phone }}">+04601 885399</a></li>
                            <li><a href="mailto:{{ $contact?->value?->email }}">{{ $contact?->value?->email ?? '' }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center text-lg-left">
                    <div class="copyright-text">
                        <p>{{ __("Copyright") }} &copy; {{ __("2023,") }} {{ __("All Right Reserved") }} <a href="{{ setRoute('index') }}"><span class="text--base">{{ $basic_settings->site_name }}</span></a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</footer>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 End Footer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
