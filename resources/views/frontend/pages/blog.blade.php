@php
    $app_local      = get_default_language_code();
@endphp 
@extends('frontend.layouts.master')

@push("css")
    
@endpush

@section('content') 
<!-- blog section -->
<section class="blog-section ptb-120">
    <div class="container">
        <div class="blog-title">
            <div class="row">
                <div class="col-lg-7">
                    <h4 class="title text--base pb-20">{{ $blog->value->language->$app_local->title ?? "" }}</h4>
                    <h2 class="title pb-20">{{ $blog->value->language->$app_local->heading ?? "" }}</h2>
                    <p>{{ $blog->value->language->$app_local->sub_heading ?? "" }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach ($blogs as $item)
                <div class="col-lg-4 col-md-6 pb-20">
                    <a href="{{ setRoute('blog.details',$item->slug) }}">
                        <div class="blog-area">
                            <div class="blog-img">
                                <img src="{{ get_image($item->data->image,'site-section') }}" alt="img">
                            </div>
                            <div class="blog-content">
                                <h3 class="content-title">{{ Str::words($item->data->language->$app_local->title ?? "","5","...") }}</h3>
                                <p>{!! Str::words($item->data->language->$app_local->description ?? '','10','...') !!}</p>
                            </div>
                            <div class="blog-btn">
                                <a href="{{ setRoute('blog.details',$item->slug) }}" class="btn--base w-100">{{ __("About More") }}</a>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        {{ get_paginate($blogs) }}
    </div>
</section>
@endsection


@push("script")
    
@endpush