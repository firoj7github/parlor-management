@php
    $app_local      = get_default_language_code();
@endphp     
@extends('frontend.layouts.master')

@push("css")
    
@endpush

@section('content') 
<section class="appointment-preview pt-150 pb-60">
    <div class="container">
        <div class="row justify-content-center mb-30-none">
            <div class="col-xl-8 col-lg-8 col-md-12 mb-30">
                <div class="booking-area">
                    <div class="content pt-0">
                        <h3 class="title"><i class="fas fa-info-circle text--base mb-20"></i> {{ __("Appointment Preview") }}</h3>
                        <div class="list-wrapper">
                            <ul class="list">
                                @php
                                    $from_time = $booking->schedule->from_time ?? '';
                                    $parsed_from_time = \Carbon\Carbon::createFromFormat('H:i', $from_time)->format('h A');

                                    $to_time   = $booking->schedule->to_time ?? '';
                                    $parsed_to_time = \Carbon\Carbon::createFromFormat('H:i', $to_time)->format('h A');
                                @endphp
                                <li>{{ __("Parlour Name") }}:<span>{{ $booking->parlour->name ?? '' }}</span></li>
                                <li>{{ __("Schedule") }}:<span>{{ $booking->schedule->week->day ?? '' }} ({{ $parsed_from_time ?? '' }} - {{ $parsed_to_time ?? '' }})</span></li>
                                <li>{{ __("Name") }}:<span>{{ $booking->name ?? '' }}</span></li>
                                <li>{{ __("Mobile") }}:<span>{{ $booking->mobile ?? '' }}</span></li>
                                <li>{{ __("Email") }}:<span>{{ $booking->email ?? '' }}</span></li>
                                
                                <li>{{ __("Type") }}:<span>{{ implode(', ', $booking->type) }}</span></li>
                                <li>{{ __("Gender") }}:<span>{{ $booking->gender ?? '' }}</span></li>
                                <li>{{ __("Payment Gateway") }}: <span>{{ $booking->payment_gateway->name }}</span></li>
                                <li>{{ __("Exchange Rate") }}: <span>{{ get_amount(get_default_currency_rate()) }} {{ get_default_currency_code() }} = {{ get_amount($booking->payment_gateway->rate) }} {{ $booking->payment_gateway->currency_code ?? '' }}</span></li>
                                <li>{{ __("Price") }}: <span>{{ $booking->price }} {{ get_default_currency_code() }}</span></li>
                                <li>{{ __("Total Price") }}: <span>{{ floatVal($booking->price) * get_amount($booking->payment_gateway->rate)  }} {{ $booking->payment_gateway->currency_code }}</span></li>
                            </ul>
                        </div>
                        <div class="btn-area mt-20">
                            <button type="submit" class="btn--base w-100">{{ __("Confirm Appointment") }} <i class="fas fa-check-circle ms-1"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
    
@endpush