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
        ]
    ], 'active' => __("All Logs")])
@endsection

@section('content')

<div class="table-area">
    <div class="table-wrapper">
        <div class="table-header">
            <h5 class="title">{{ __($page_title) }}</h5>
            <div class="table-btn-area">
                @include('admin.components.search-input',[
                    'name'  => 'user_search',
                ])
            </div>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>{{ __("MTCN ID") }}</th>
                        <th>{{ __("Parlour Name") }}</th>
                        <th>{{ __("Price") }}</th>
                        <th>{{ __("P. Method") }}</th>
                        <th>{{ __("Status") }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    
                    @forelse ($data ?? [] as $key => $item)
                        <tr>
                            <td>{{ $item->trx_id ?? '' }}</td>
                            <td>{{ $item->parlour->name ?? '' }}</td>
                            <td>{{ get_default_currency_symbol() }}{{ get_amount($item->price) }}</td>
                            <td>{{ $item->payment_method ?? '' }}</td>
                            <td>
                                @if ($item->status == global_const()::PARLOUR_BOOKING_STATUS_PENDING)
                                    <span>{{ __("Pending") }}</span>
                                @elseif ($item->status == global_const()::PARLOUR_BOOKING_STATUS_CONFIRM_PAYMENT)
                                    <span>{{ __("Confirm Payment") }}</span>
                                @elseif ($item->status == global_const()::PARLOUR_BOOKING_STATUS_HOLD)
                                    <span>{{ __("On Hold") }}</span>
                                @elseif ($item->status == global_const()::PARLOUR_BOOKING_STATUS_SETTLED)
                                    <span>{{ __("Settled") }}</span>
                                @elseif ($item->status == global_const()::PARLOUR_BOOKING_STATUS_COMPLETE)
                                    <span>{{ __("Completed") }}</span>
                                @elseif ($item->status == global_const()::PARLOUR_BOOKING_STATUS_CANCEL)
                                    <span>{{ __("Canceled") }}</span>
                                @elseif ($item->status == global_const()::PARLOUR_BOOKING_STATUS_FAILED)
                                    <span>{{ __("Failed") }}</span>
                                @elseif ($item->status == global_const()::PARLOUR_BOOKING_STATUS_REFUND)
                                    <span>{{ __("Refunded") }}</span>
                                @else
                                    <span>{{ __("Delayed") }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ setRoute('admin.parlour.booking.details',$item->trx_id) }}" class="btn btn--base btn--primary"><i class="las la-info-circle"></i></a>
                                
                            </td>
                        </tr>
                    @empty
                        @include('admin.components.alerts.empty',['colspan' => 6])
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ get_paginate($data) }}
</div>

@endsection