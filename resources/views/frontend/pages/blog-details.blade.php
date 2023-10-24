@php
    $app_local      = get_default_language_code();
@endphp 
@extends('frontend.layouts.master')

@push("css")
    
@endpush

@section('content') 

<section class="blog-section blog-details-section pt-150 pb-40">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-5 mb-30">
                <div class="blog-sidebar">
                    <div class="widget-box mb-30">
                        <h4 class="widget-title">{{ __("Categories") }}</h4>
                        <div class="category-widget-box">
                            <ul class="category-list">
                                @foreach ($category as $item)
                                    <li><a href="javascript:void(0)">{{ $item->name->language->$app_local->name ?? "" }}<span>{{ $item->blog_count }}</span></a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="widget-box mb-30">
                        <h4 class="widget-title">{{ __("Recent Posts") }}</h4>
                        <div class="popular-widget-box">
                            @foreach ($recent_posts as $item)
                                <div class="single-popular-item d-flex flex-wrap align-items-center">
                                    <div class="popular-item-thumb">
                                        <a href="{{ setRoute('blog.details',$item->slug) }}"><img src="{{ get_image($item->data->image , 'site-section') }}" alt="blog"></a>
                                    </div>
                                    @php
                                        $date = $item->created_at ?? "";
                                        $formattedDate = date('M d, Y', strtotime($date));
                                    @endphp
                                    <div class="popular-item-content">
                                        <span class="date">{{ $formattedDate }}</span>
                                        <h6 class="title"><a href="{{ setRoute('blog.details',$item->slug) }}">{{ Str::words($item->data->language->$app_local->title ?? "","5","...") }}</a></h6>
                                    </div>
                                </div>
                            @endforeach 
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-8">
                <div class="blog-item">
                    <div class="blog-thumb">
                        <img src="{{ get_image($blog->data->image , 'site-section') }}" alt="blog">
                    </div>
                    <div class="blog-content pt-3=4">
                        <h3 class="title">{{ $blog->data->language->$app_local->title ?? "" }}</h3>
                        <p>{!! $blog->data->language->$app_local->description ?? "" !!}</p>
                        <div class="blog-tag-wrapper">
                            <span>{{ __("Tags") }}:</span>
                            @php
                                $tags    = $blog->data->language->$app_local->tags ?? [];
                            @endphp
                            <ul class="blog-footer-tag">
                                @foreach ($tags as $item)
                                    <li><a href="javascript:void(0)">{{ $item }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection