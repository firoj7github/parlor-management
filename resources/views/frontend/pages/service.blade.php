@php
    $app_local      = get_default_language_code();
@endphp
@extends('frontend.layouts.master')

@push("css")
    
@endpush

@section('content') 
<!-- service -->
<section class="service-section pt-150 pb-80">
    <div class="container">
        <h4 class="title text--base pb-20">{{ $service->value->language->$app_local->title ?? "" }}</h4>
        <h2 class="title">{{ $service->value->language->$app_local->heading ?? "" }}</h2>
        <div class="row pt-20">
            @foreach ($service->value->items ?? [] as $item)
                @if ($item->status == true)
                    <div class="col-xl-3 col-lg-4 col-md-6 pb-30">
                        <div class="service-area">
                            <div class="service-icon">
                                <i class="{{ $item->icon ?? ''}}"></i>
                            </div>
                            <div class="service-type">
                                <h3 class="title">{{ $item->language->$app_local->item_title ?? '' }}</h3>
                                <p>{{ $item->language->$app_local->item_heading ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endsection


@push("script")
    
@endpush