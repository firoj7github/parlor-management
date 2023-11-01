@extends('frontend.layouts.master')

@push("css")
    
@endpush

@section('content') 
    @include('frontend.section.banner')
    @include('frontend.section.banner-search')
    @include('frontend.section.search-result')
    @include('frontend.section.how-it-work')
    @include('frontend.section.testimonial')
    @include('frontend.section.statistics')
    @include('frontend.section.photo-gallery')
    @include('frontend.section.download-app')
@endsection


@push("script")
    
@endpush