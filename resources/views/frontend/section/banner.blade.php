@php
    $app_local      = get_default_language_code();
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Banner Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div class="banner-slider">
    <div class="swiper-wrapper">
        @foreach ($sliders?->value?->items ?? [] as $item)
        {{-- @dd($item->image) --}}
            @if ($item->status == 1)
                <div class="swiper-slide">
                    {{-- <div class="banner-section bg_img" data-background="{{ get_image($item->image, 'site-section') ?? '' }}"> --}}
                    <div class="banner-section bg_img" data-background="{{ asset($item->image) ?? '' }}">

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
</div>


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Banner Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
