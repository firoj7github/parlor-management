@php
    $app_local      = get_default_language_code();
@endphp
<!-- Testimonials Section  -->
<section class="testimonial-section ptb-60">
    <div class="container">
        <div class="row">
            <div class="col-xl-7 col-lg-7 pb-3">
                <div class="section-header">
                    <h4 class="titte text--base pb-20">{{ $testimonial->value->language->$app_local->title ?? '' }}</h4>
                    <h2 class="section-title">{{ $testimonial->value->language->$app_local->heading ?? '' }}</h2>
                    <p>{{ $testimonial->value->language->$app_local->sub_heading ?? '' }}</p>
                </div>
            </div>
        </div>
        <div class="testimonial-area">
            <div class="testimonial-slider">
                <div class="swiper-wrapper">
                    @if(isset($testimonial->value->items))
                        @foreach ($testimonial->value->items ?? [] as $key=>$item)
                            <div class="swiper-slide">
                                <div class="testimonial-wrapper">
                                    <div class="testimonial-thumb">
                                        {{-- @dd($item->image); --}}
                                        {{-- <img src="{{ get_image($item->image,'site-section') }}" alt="client"> --}}
                                        <img src="{{ asset('frontend/images/site-section/' . $item->image) }}" alt="client">

                                    </div>
                                    <div class="testimonial-content">
                                        <div class="testimonial-ratings">
                                            @for ($initial = 1; $initial <= $item->rating; $initial++)
                                                <i class="fas fa-star"></i>
                                            @endfor
                                        </div>
                                        <p>{{ $item->language->$app_local->comment ?? '' }}</p>
                                    <div class="client-title">
                                            <h4 class="title">{{ $item->name ?? '' }}</h4>
                                            <P>{{ $item->designation ?? '' }}</P>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                @if ($testimonial?->value?->items)
                    <div class="swiper-pagination"></div>
                @endif
            </div>
        </div>
    </div>
</section>
