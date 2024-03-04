@php
    $app_local      = get_default_language_code();
@endphp
@extends('frontend.layouts.master')

@push("css")

@endpush

@section('content')
<!-- about section -->
<section class="about-section pt-150 pb-60">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 pb-40">
                <div class="about-img">
                    {{-- <img src="{{ get_image($about?->value?->image,'site-section') ?? '' }}" alt="img"> --}}
                    <img src="{{ asset('frontend/images/site-section/' . $about?->value?->image) }}" alt="client">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content">
                    <h4 class="title text--base pb-20">{{ $about->value->language->$app_local->title ?? '' }}</h4>
                    <h2 class="title pb-20">{{ $about->value->language->$app_local->heading ?? '' }}</h2>
                    <p class="sub-title">{{ $about->value->language->$app_local->sub_heading ?? '' }}</p>
                    <ul class="about-type pb-10">
                        @foreach ($about?->value?->items ?? [] as $item)
                            <li class="about-item text--base">{{ $item->language->$app_local->item_title ?? '' }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Faq
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="faq-section ptb-80">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-xl-6 col-lg-6 mb-30">
                <div class="section-header-title mb-30">
                    <h4 class="title text--base pb-20">{{ $faq->value->language->$app_local->title ?? "" }}</h4>
                    <h2 class="title-head pb-20">{{ $faq->value->language->$app_local->heading ?? "" }}</h2>
                </div>
                <div class="faq-wrapper">
                    @foreach ($faq?->value?->items ?? [] as $item)
                        @if ($item->status == true)
                            <div class="faq-item">
                                <h3 class="faq-title"><span class="title">{{ $item->language->$app_local->question ?? "" }}</span><span class="right-icon"></span></h3>
                                <div class="faq-content">
                                    <p>{{ $item->language->$app_local->answer ?? "" }}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="faq-img text-center">
                    {{-- <img src="{{ get_image($faq?->value?->image , 'site-section') ?? '' }}" alt="img"> --}}
                    <img src="{{ asset('frontend/images/site-section/' . $faq?->value?->image) }}" alt="client">

                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Faq
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@endsection
@push("script")

@endpush
