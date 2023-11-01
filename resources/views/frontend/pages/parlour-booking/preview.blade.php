@php
    $app_local      = get_default_language_code();
@endphp     
@extends('frontend.layouts.master')

@push("css")
    
@endpush

@section('content') 
<section class="appointment-preview pt-150 pb-60">
    <div class="container">
        <form action="{{ setRoute('parlour.booking.confirm',$booking->slug) }}" method="POST">
        @csrf
            <div class="row justify-content-center mb-30-none">
                <div class="col-xl-8 col-lg-8 col-md-12 mb-30">
                    <div class="booking-area">
                        <div class="content pt-0">
                            <h3 class="title"><i class="fas fa-info-circle text--base mb-20"></i>{{ __("Appointment Preview") }}</h3>
                            <div class="list-wrapper">
                                <div class="preview-area">
                                    <div class="preview-item">
                                    <p>{{ __("Parlour Name") }} :</p>
                                    </div>
                                    <div class="preview-details">
                                        <p>{{ @$booking->parlour->name ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="preview-area">
                                    <div class="preview-item">
                                    <p>{{ __("Service Type") }} :</p>
                                    </div>
                                    <div class="preview-details">
                                        <p>{{ implode(', ',@$booking->service) }}</p>
                                    </div>
                                </div>
                                <div class="preview-area">
                                    <div class="preview-item">
                                    <p>{{ __("Schedule") }} :</p>
                                    </div>
                                    <div class="preview-details">
                                        <p>{{ @$booking->date ?? '' }} ({{ $booking->schedule->from_time }} - {{ $booking->schedule->to_time }})</p>
                                    </div>
                                </div>
                                <div class="preview-area">
                                    <div class="preview-item">
                                    <p>{{ __("Amount") }} :</p>
                                    </div>
                                    <div class="preview-details">
                                        <p>{{ get_default_currency_symbol() }}{{ get_amount(@$booking->price) ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="preview-area">
                                    <div class="preview-item">
                                    <p>{{ __("Fees & Charges") }} :</p>
                                    </div>
                                    <div class="preview-details">
                                        <p>{{ get_default_currency_symbol() }}{{ get_amount(@$booking->total_charge) ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="preview-area">
                                    <div class="preview-item">
                                    <p>{{ __("Total Payable Amount") }} :</p>
                                    </div>
                                    <div class="preview-details">
                                        <p>{{ get_default_currency_symbol() }}{{ get_amount(@$booking->payable_price) ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="payment-type pt-4">
                                <div class="form-group">
                                    <h5 class="title">{{ __("Select Payment Method") }}<span>*</span></h5>
                                    <div class="radio-wrapper pt-2" id="pg-view">
                                        <div class="radio-item">
                                            <input type="radio" id="level" class="hide-input" value="{{ global_const()::CASH_PAYMENT }}" checked name="payment_method">
                                            <label for="level"><img src="{{ asset("public/frontend/images/cashpay.png") }}" alt="icon">{{ __("Cash-Payment") }}</label>
                                        </div>
                                        @foreach ($payment_gateway as $item)
                                            <div class="radio-item">
                                                <input type="radio" id="level_{{ $item->id }}" class="hide-input" name="payment_method" value="{{ $item->id }}">
                                                <label for="level_{{ $item->id }}"><img src="{{ get_image($item->image ,'payment-gateways') }}" alt="icon">{{ $item->gateway->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="btn-area mt-30">
                                <button type="submit" class="btn--base w-100">{{ __("Confirm Booking") }} <i class="fas fa-check-circle ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
@push('script')

<script>
    $(document).ready(function() {
        var countdownDuration = '{{ global_const()::BOOKING_EXP_SEC }}';

        function updateCountdown() {
            countdownDuration--;
    
            if (countdownDuration >= 0) {
                setTimeout(updateCountdown, 1000);
            } else {
                deleteBooking();
            }
        }

        
        function deleteBooking() {
            $.ajax({
                method: 'POST',
                url: '{{ route("parlour.booking.delete") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    bookingSlug: '{{ $booking->slug }}'
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = '{{ route("find.parlour") }}';
                    } else {
                        
                    }
                }
            });
            console.log("delete");
        }
        
        updateCountdown();
    });
</script>  
@endpush