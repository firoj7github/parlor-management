@extends('user.layouts.master')

@push('css')
    
@endpush
@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Profile"),
            'url'   => setRoute("user.profile.index"),
        ]
    ], 'active' => __("My History")])
@endsection
@section('content')
<div class="body-wrapper">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="appointment-area">
                <h3 class="title"><i class="fas fa-info-circle text--base mb-20"></i> {{ __("Booking Details") }}</h3>
                <div class="salon-thumb">
                    <img src="{{ get_image($data->parlour->image,'site-section') }}" alt="img">
                </div>
                <div class="about-details">
                    <div class="salon-title">
                        <h3 class="title"><i class="las la-user-alt"></i> {{ __("Parlour Information") }}</h3>
                    </div>
                    <div class="list-wrapper">
                        <div class="preview-area">
                            <div class="preview-item">
                               <p>{{ __("Parlour Name") }} :</p>
                            </div>
                            <div class="preview-details">
                                <p>{{ $data->parlour->name ?? '' }}</p>
                            </div>
                        </div>
                        <div class="preview-area">
                            <div class="preview-item">
                               <p>{{ __("Contact") }} :</p>
                            </div>
                            <div class="preview-details">
                                <p>{{ $data->parlour->contact ?? '' }}</p>
                            </div>
                        </div>
                        <div class="preview-area">
                            <div class="preview-item">
                               <p>{{ __("Parlour Address") }} :</p>
                            </div>
                            <div class="preview-details">
                                <p>{{ $data->parlour->address ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="salon-title pt-4">
                        <h3 class="title"><i class="las la-street-view"></i> {{ __("Service Information") }}</h3>
                    </div>
                    <div class="list-wrapper">
                        <div class="preview-area">
                            <div class="preview-item">
                               <p>{{ __("Service Name") }} :</p>
                            </div>
                            <div class="preview-details">
                                <p>{{ implode(', ',$data->service) ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="shedule-title pt-4">
                        <h3 class="title"><i class="fas fa-history"> </i> {{ __("Schedule Information") }}</h3>
                    </div>
                    <div class="list-wrapper">
                        <div class="preview-area">
                            <div class="preview-item">
                               <p>{{ __("Date") }} :</p>
                            </div>
                            <div class="preview-details">
                                <p>{{ $data->date }}</p>
                            </div>
                        </div>
                        <div class="preview-area">
                            <div class="preview-item">
                               <p>{{ __("Time") }} :</p>
                            </div>
                            <div class="preview-details">
                                <p>{{ $data->schedule->from_time ?? '' }} - {{ $data->schedule->to_time ?? '' }}</p>
                            </div>
                        </div>
                        <div class="preview-area">
                            <div class="preview-item">
                               <p>{{ __("Serial Number") }} :</p>
                            </div>
                            <div class="preview-details">
                                <p>{{ $data->serial_number ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="shedule-title pt-4">
                        <h3 class="title"><i class="las la-comment-dollar"></i> {{ __("Payment Information") }}</h3>
                    </div>
                    <div class="list-wrapper">
                        <div class="preview-area">
                            <div class="preview-item">
                               <p>{{ __("MTCN Number") }} :</p>
                            </div>
                            <div class="preview-details">
                                <p>{{ $data->trx_id ?? '' }}</p>
                            </div>
                        </div>
                        <div class="preview-area">
                            <div class="preview-item">
                               <p>{{ __("Payment Method") }} :</p>
                            </div>
                            <div class="preview-details">
                                <p>{{ $data->payment_method ?? '' }}</p>
                            </div>
                        </div>
                        <div class="preview-area">
                            <div class="preview-item">
                               <p>{{ __("Total Service Price") }} :</p>
                            </div>
                            <div class="preview-details">
                                <p>{{ get_default_currency_symbol() }}{{ get_amount($data->price) }}</p>
                            </div>
                        </div>
                        <div class="preview-area">
                            <div class="preview-item">
                               <p>{{ __("Fees & Charges") }} :</p>
                            </div>
                            <div class="preview-details">
                                <p>{{ get_default_currency_symbol() }}{{ get_amount($data->total_charge) }}</p>
                            </div>
                        </div>
                        <div class="preview-area">
                            <div class="preview-item">
                               <p>{{ __("Total Payable Price") }} :</p>
                            </div>
                            <div class="preview-details">
                                <p>{{ get_default_currency_symbol() }}{{ get_amount($data->payable_price) }}</p>
                            </div>
                        </div>
                        <div class="preview-area">
                            <div class="preview-item">
                               <p>{{ __("Status") }} :</p>
                            </div>
                            <div class="preview-details">
                                @if ($data->status == global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)
                                    <span>{{ __("Review Payment") }}</span> 
                                @elseif ($data->status == global_const()::PARLOUR_BOOKING_STATUS_PENDING)
                                    <span>{{ __("Pending") }}</span>
                                @elseif ($data->status == global_const()::PARLOUR_BOOKING_STATUS_CONFIRM_PAYMENT)
                                    <span>{{ __("Confirm Payment") }}</span>
                                @elseif ($data->status == global_const()::PARLOUR_BOOKING_STATUS_HOLD)
                                    <span>{{ __("On Hold") }}</span>
                                @elseif ($data->status == global_const()::PARLOUR_BOOKING_STATUS_SETTLED)
                                    <span>{{ __("Settled") }}</span>
                                @elseif ($data->status == global_const()::PARLOUR_BOOKING_STATUS_COMPLETE)
                                    <span>{{ __("Completed") }}</span>
                                @elseif ($data->status == global_const()::PARLOUR_BOOKING_STATUS_CANCEL)
                                    <span>{{ __("Canceled") }}</span>
                                @elseif ($data->status == global_const()::PARLOUR_BOOKING_STATUS_FAILED)
                                    <span>{{ __("Failed") }}</span>
                                @elseif ($data->status == global_const()::PARLOUR_BOOKING_STATUS_REFUND)
                                    <span>{{ __("Refunded") }}</span>
                                @else
                                    <span>{{ __("Delayed") }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection