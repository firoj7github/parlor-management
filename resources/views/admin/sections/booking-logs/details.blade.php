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
                        
                        <li>{{ __("Sending Amount") }}<span>{{ get_amount($transaction->PARLOUR_BOOKING_data->send_money) ?? '' }} {{ $sender_currency->code ?? '' }}</span></li>
                        <li>{{ __("Exchange Rate") }}<span>{{ get_amount($sender_currency->rate) }} {{ $sender_currency->code}} = {{ get_amount($receiver_currency->rate) }} {{ $receiver_currency->code}}</span></li>
                        <li>{{ __("Total Fees & Charges") }}<span>{{ get_amount($transaction->fees) ?? "" }} {{ $sender_currency->code }}</span></li>
                        <li>{{ __("Amount we'll Convert") }}<span>{{ get_amount($transaction->PARLOUR_BOOKING_data->convert_amount) ?? "" }} {{ $sender_currency->code }}</span></li>
                        <li>{{ __("Will Get Amount") }}<span>{{ get_amount($transaction->will_get_amount) ?? '' }} {{ $receiver_currency->code }}</span></li>
                        <li>{{ __("Sending Purpose") }}<span>{{ $transaction->PARLOUR_BOOKING_data->sending_purpose ?? '' }}</span></li>
                        <li>{{ __("Source Of Fund") }}<span>{{ $transaction->PARLOUR_BOOKING_data->source ?? '' }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-30">
        <div class="transaction-area">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="title mb-0"><i class="fas fa-user text--base me-2"></i>{{ __("Receipt Summary ") }}</h4>
            </div>
            <div class="content pt-0">
                <div class="list-wrapper">
                    <ul class="list">
                        <li>{{ __("Recipient Name") }}<span>{{ $transaction->PARLOUR_BOOKING_data->first_name ?? '' }} {{ $transaction->PARLOUR_BOOKING_data->middle_name ?? 'N/A' }} {{ $transaction->PARLOUR_BOOKING_data->last_name ?? '' }}</span></li>
                        <li>{{ __("Recipient Email") }}<span>{{ $transaction->PARLOUR_BOOKING_data->email ?? 'N/A' }}</span></li>
                        <li>{{ __("Phone Number") }}<span>{{ $transaction->PARLOUR_BOOKING_data->phone ?? 'N/A' }}</span></li>
                        <li>{{ __("Country") }}<span>{{ $transaction->PARLOUR_BOOKING_data->country ?? '' }}</span></li>
                        <li>{{ __("State & City") }}<span class="text--warning">{{ $transaction->PARLOUR_BOOKING_data->city ?? "N/A" }} </span><span class="ms-1">({{ $transaction->PARLOUR_BOOKING_data->state ?? "N/A" }})</span></li>
                        <li>{{ __("Zip Code") }}<span>{{ $transaction->PARLOUR_BOOKING_data->zip_code ?? 'N/A'  }}</span></li>
                        <li>{{ __("Address") }}<span>{{ $transaction->PARLOUR_BOOKING_data->address ?? 'N/A'  }}</span></li>
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mb-30">
        <div class="transaction-area">
            <div class="d-flex justify-content-between align-items-center">

                <h4 class="title"><i class="fas fa-user text--base me-2"></i>{{ __("Payment Summary") }}</h4>
                <div class="d-flex">
                    <div class="button-link me-2">
                        <input type="hidden" name="" class="box" value="{{ setRoute('share.link',$transaction->trx_id) }}">
                        <div class="btn btn--base d-flex copy"><i class="las la-share"></i> {{ __("Copy Link") }}</div>
                    </div>
                    <div class="button-link">
                        <a href="{{ setRoute('admin.download.pdf',$transaction->trx_id) }}" class="btn btn--base d-flex"><i class="las la-file-pdf"></i> {{ __("Download Receipt") }}</a>
                    </div>
                </div>
                
            </div>
            
            <div class="content pt-0">
                <div class="list-wrapper">
                    <ul class="list">
                        <li>{{ __("MTCN Number") }} <span>{{ $transaction->trx_id ?? ''  }}</span> </li>
                        <li>{{ __("Transaction Type") }} <span>{{ $transaction->PARLOUR_BOOKING_data->type ?? ''  }}</span> </li>
                        <li>{{ __("Method Name") }} <span>{{ $transaction->PARLOUR_BOOKING_data->method_name ?? ''  }}</span> </li>
                        <li>{{ __("Account Number") }} <span>{{ $transaction->PARLOUR_BOOKING_data->account_number ?? ''  }}</span> </li>
                        <li>{{ __("Payment Method") }} <span>{{ $transaction->PARLOUR_BOOKING_data->currency->name ?? ''  }}</span> </li>
                        <li>{{ __("Exchange Rate") }} <span>{{ get_amount($sender_currency->rate) }} {{ $sender_currency->code}} = {{ get_amount($transaction->exchange_rate) }} {{ $transaction->PARLOUR_BOOKING_data->currency->code}}</span> </li>
                        <li>{{ __("Payable Amount") }} <span>{{ get_amount($transaction->payable) ?? '' }} {{ $transaction->PARLOUR_BOOKING_data->currency->code}}</span> </li>
                        <li>{{ __("Payment Status") }}
                            @if ($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)
                                <span>{{ __("Review Payment") }}</span> 
                            @elseif ($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_PENDING)
                                <span>{{ __("Pending") }}</span>
                            @elseif ($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_CONFIRM_PAYMENT)
                                <span>{{ __("Confirm Payment") }}</span>
                            @elseif ($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_HOLD)
                                <span>{{ __("On Hold") }}</span>
                            @elseif ($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_SETTLED)
                                <span>{{ __("Settled") }}</span>
                            @elseif ($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_COMPLETE)
                                <span>{{ __("Completed") }}</span>
                            @elseif ($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_CANCEL)
                                <span>{{ __("Canceled") }}</span>
                            @elseif ($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_FAILED)
                                <span>{{ __("Failed") }}</span>
                            @elseif ($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_REFUND)
                                <span>{{ __("Refunded") }}</span>
                            @else
                                <span>{{ __("Delayed") }}</span>
                            @endif
                            
                        </li>
                        <li>{{ __("Remark") }} <span>{{ $transaction->PARLOUR_BOOKING_data->remark ?? 'N/A' }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ setRoute('admin.send.PARLOUR_BOOKING.status.update',$transaction->trx_id) }}" method="post">
        @csrf
        <div class="col-lg-12 mb-30">
            <div class="transaction-area">
                <h4 class="title"><i class="fas fa-user text--base me-2"></i>{{ __("Progress of PARLOUR_BOOKING Transactions") }}</h4>
                <div class="content pt-0">
                    <div class="radio-area">
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-1" value="1" @if($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT) checked @endif name="status">
                                <label for="level-1">{{ __("Review Payment") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-2" value="2" @if($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_PENDING) checked @endif name="status">
                                <label for="level-2">{{ __("Pending") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-3" value="3" @if($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_CONFIRM_PAYMENT) checked @endif name="status">
                                <label for="level-3">{{ __("Confirm Payment") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-4" value="4" @if($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_HOLD) checked @endif name="status">
                                <label for="level-4">{{ __("On Hold") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-5" value="5" @if($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_SETTLED) checked @endif name="status">
                                <label for="level-5">{{ __("Settled") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-6" value="6" @if($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_COMPLETE) checked @endif name="status">
                                <label for="level-6">{{ __("Completed") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-7" value="7" @if($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_CANCEL) checked @endif name="status">
                                <label for="level-7">{{ __("Canceled") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-8" value="8" @if($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_FAILED) checked @endif name="status">
                                <label for="level-8">{{ __("Failed") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-9" value="9" @if($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_REFUND) checked @endif name="status">
                                <label for="level-9">{{ __("Refunded") }}</label>
                            </div>
                        </div>
                        <div class="radio-wrapper">
                            <div class="radio-item">
                                <input type="radio" id="level-10" value="10" @if($transaction->status == global_const()::PARLOUR_BOOKING_STATUS_DELAYED) checked @endif name="status">
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