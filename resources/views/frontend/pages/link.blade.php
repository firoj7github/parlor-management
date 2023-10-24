@extends('frontend.layouts.master')
@php
    $app_local  = get_default_language_code();
@endphp


@section('content')
<div class="contact-section ptb-120">
    <div class="container">
        <p>{!! $link->content->language?->$app_local?->content ?? "" !!}</p>
    </div>
</div>

@endsection