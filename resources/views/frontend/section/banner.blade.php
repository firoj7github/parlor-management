@php
    $app_local      = get_default_language_code();
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Banner Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div class="banner-slider">
    <!-- <div class="swiper-wrapper">
        @foreach ($sliders?->value?->items ?? [] as $item)
        {{-- @dd($item->image) --}}
            @if ($item->status == 1)
                <div class="swiper-slide">
                    {{-- <div class="banner-section bg_img" data-background="{{ get_image($item->image, 'site-section') ?? '' }}"> --}}
                    <div class="banner-section bg_img" data-background="{{ $item->image ? asset('frontend/images/baner/' . $item->image) : '' }}">


                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="banner-content">
                                        <h1 class="title">{{ $item->language->$app_local->heading ?? '' }}</h1>
                                        <p>{{ $item->language->$app_local->sub_heading ?? '' }}</p>
                                        <div class="banner-btn mt-40">
                                            <a href="{{ setRoute('find.parlour') }}" class="btn--base">{{ $item->button_name ?? '' }} </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    <div class="slider-prev slider-nav">
        <i class="las la-angle-left"></i>
    </div>
    <div class="slider-next slider-nav">
        <i class="las la-angle-right"></i>
    </div>
</div> -->
<div class="banner-slider">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <div class="banner-section bg_img" data-background="{{ asset('/frontend/images/baner/banner-2.webp') ?? '' }}">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="banner-content">
                                <h1 class="title">Your Beauty, Your Way, Your eSalon</h1>
                                <p>Discover a world of personalized beauty at your fingertips. With eSalon, it’s all about you. Book your salon appointment effortlessly and indulge in a salon experience tailored to your unique style and needs.</p>
                                <div class="banner-btn mt-40">
                                    <a href="{{ setRoute('find.parlour') }}" class="btn--base">Find Parlour</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-slide">
            <div class="banner-section bg_img" data-background="{{ asset('/frontend/images/baner/banner-1.webp') ?? '' }}">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="banner-content">
                                <h1 class="title">Where Beauty Meets Convenience</h1>
                                <p>eSalon, it’s all about you. Book your salon appointment effortlessly and indulge in a salon experience tailored to your unique style and needs.</p>
                                <div class="banner-btn mt-40">
                                    <a href="{{ setRoute('find.parlour') }}" class="btn--base">Find Parlour</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-slide">
            <div class="banner-section bg_img" data-background="{{ asset('/frontend/images/baner/banner-3.webp') ?? '' }}">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="banner-content">
                                <h1 class="title">Elevate Your Beauty Journey</h1>
                                <p>Book your salon appointment effortlessly and indulge in a salon experience tailored to your unique style and needs.</p>
                                <div class="banner-btn mt-40">
                                    <a href="{{ setRoute('find.parlour') }}" class="btn--base">Find Parlour</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="slider-prev slider-nav">
        <i class="las la-angle-left"></i>
    </div>
    <div class="slider-next slider-nav">
        <i class="las la-angle-right"></i>
    </div>
</div>

    
</div>


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Banner Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
