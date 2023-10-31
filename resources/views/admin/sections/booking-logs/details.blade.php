@extends('admin.layouts.master')

@push('css')

    <style>
        .fileholder {
            min-height: 374px !important;
        }

        .fileholder-files-view-wrp.accept-single-file .fileholder-single-file-view,.fileholder-files-view-wrp.fileholder-perview-single .fileholder-single-file-view{
            height: 330px !important;
        }
    </style>
@endpush

@section('page-title')
    @include('admin.components.page-title',['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("admin.dashboard"),
        ],
        
    ], 'active' => __("Booking Details")])
@endsection

@section('content')
<div class="row mb-30-none">
    
    <div class="col-lg-6 mb-30">
        <div class="transaction-area">
            <h4 class="title mb-0"><i class="fas fa-user text--base me-2"></i>{{ __("Parlour Information") }}</h4>
            <div class="content pt-0">
                <div class="list-wrapper">
                    <ul class="list">
                        <li>{{ __("Parlour Name") }}<span>{{ $data->parlour->name ?? '' }}</span></li>
                        <li>{{ __("Manager Name") }}<span>{{ $data->parlour->manager_name ?? '' }}</span></li>
                        <li>{{ __("Experience") }}<span>{{ $data->parlour->experience ?? '' }}</span></li>
                        <li>{{ __("Contact") }}<span>{{ $data->parlour->contact ?? '' }}</span></li>
                        <li>{{ __("Address") }}<span>{{ $data->parlour->address ?? '' }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-30">
        <div class="transaction-area">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="title mb-0"><i class="fas fa-user text--base me-2"></i>{{ __("Service & Schedule Information") }}</h4>
            </div>
            <div class="content pt-0">
                <div class="list-wrapper">
                    <ul class="list">
                        <li>{{ __("Service Name") }}<span>{{ implode(', ',$data->service) ?? '' }}</span></li>
                        <li>{{ __("Date") }}<span>{{ $data->date ?? '' }}</span></li>
                        <li>{{ __("Time") }}<span>{{ $data->schedule->from_time ?? '' }} - {{ $data->schedule->to_time ?? '' }}</span></li>
                        <li>{{ __("Serial Number") }}<span>{{ $data->serial_number ?? '' }}</span></li>
                        <li>{{ __("Status") }}
                            @if ($data->status == global_const()::PARLOUR_BOOKING_STATUS_PENDING)
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
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mb-30">
        <div class="transaction-area">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="title mb-0"><i class="fas fa-user text--base me-2"></i>{{ __("Payment Information") }}</h4>
            </div>
            <div class="content pt-0">
                <div class="list-wrapper">
                    <ul class="list">
                        <li>{{ __("MTCN Number") }} <span>{{ $data->trx_id ?? ''  }}</span> </li>
                        <li>{{ __("Payment Method") }} <span>{{ $data->payment_method ?? ''  }}</span> </li>
                        <li>{{ __("Service Price") }} <span>{{ get_default_currency_symbol() }}{{ get_amount($data->price) }}</span> </li>
                        <li>{{ __("Fees & Charges") }} <span>{{ get_default_currency_symbol() }}{{ get_amount($data->total_charge) }}</span> </li>
                        <li>{{ __("Toatl Payable Price") }} <span>{{ get_default_currency_symbol() }}{{ get_amount($data->payable_price) }}</span> </li>
                        <li>{{ __("Remark") }} <span>{{ $data->remark ?? 'N/A' }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ setRoute('admin.parlour.booking.status.update',$data->trx_id) }}" method="post">
        @csrf
        <div class="col-lg-12 mb-30">
            <div class="transaction-area">
                <h4 class="title"><i class="fas fa-user text--base me-2"></i>{{ __("Progress of Parlour Bookings") }}</h4>
                <div class="content pt-0">
                    <div class="radio-area">
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-2" value="{{ global_const()::PARLOUR_BOOKING_STATUS_PENDING }}" @if($data->status == global_const()::PARLOUR_BOOKING_STATUS_PENDING) checked @endif name="status">
                                <label for="level-2">{{ __("Pending") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-3" value="{{ global_const()::PARLOUR_BOOKING_STATUS_CONFIRM_PAYMENT }}" @if($data->status == global_const()::PARLOUR_BOOKING_STATUS_CONFIRM_PAYMENT) checked @endif name="status">
                                <label for="level-3">{{ __("Confirm Payment") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-4" value="{{ global_const()::PARLOUR_BOOKING_STATUS_HOLD }}" @if($data->status == global_const()::PARLOUR_BOOKING_STATUS_HOLD) checked @endif name="status">
                                <label for="level-4">{{ __("On Hold") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-5" value="{{ global_const()::PARLOUR_BOOKING_STATUS_SETTLED }}" @if($data->status == global_const()::PARLOUR_BOOKING_STATUS_SETTLED) checked @endif name="status">
                                <label for="level-5">{{ __("Settled") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-6" value="{{ global_const()::PARLOUR_BOOKING_STATUS_COMPLETE }}" @if($data->status == global_const()::PARLOUR_BOOKING_STATUS_COMPLETE) checked @endif name="status">
                                <label for="level-6">{{ __("Completed") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-7" value="{{ global_const()::PARLOUR_BOOKING_STATUS_CANCEL }}" @if($data->status == global_const()::PARLOUR_BOOKING_STATUS_CANCEL) checked @endif name="status">
                                <label for="level-7">{{ __("Canceled") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-8" value="{{ global_const()::PARLOUR_BOOKING_STATUS_FAILED }}" @if($data->status == global_const()::PARLOUR_BOOKING_STATUS_FAILED) checked @endif name="status">
                                <label for="level-8">{{ __("Failed") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-9" value="{{ global_const()::PARLOUR_BOOKING_STATUS_REFUND }}" @if($data->status == global_const()::PARLOUR_BOOKING_STATUS_REFUND) checked @endif name="status">
                                <label for="level-9">{{ __("Refunded") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-10" value="{{ global_const()::PARLOUR_BOOKING_STATUS_DELAYED }}" @if($data->status == global_const()::PARLOUR_BOOKING_STATUS_DELAYED) checked @endif name="status">
                                <label for="level-10">{{ __("Delayed") }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.button.form-btn',[
                            'class'         => "w-100 btn-loading",
                            'text'          => "Update",
                        ])
                    </div>
                </div>
            </div>
        </div>
    </form> 
    
</div>
@endsection
@push('script')

@endpush