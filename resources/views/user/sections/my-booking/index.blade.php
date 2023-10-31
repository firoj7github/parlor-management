@extends('user.layouts.master')

@push('css')
    
@endpush
@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Profile"),
            'url'   => setRoute("user.profile.index"),
        ]
    ], 'active' => __("My Booking")])
@endsection
@section('content')
<div class="body-wrapper">
    <div class="table-area mt-10">
        <div class="table-wrapper">
            <div class="dashboard-header-wrapper">
                <h4 class="title">{{ __("Booking List") }}</h4>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __("Parlour Name") }}</th>
                            <th>{{ __("Service") }}</th>
                            <th>{{ __("Schedule") }}</th>
                            <th>{{ __("Price") }}</th>
                            <th>{{ __("Status") }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data ?? [] as $item)
                        <tr>
                            <td>{{ $item->parlour->name ?? '' }}</td>
                            <td>{{ implode(', ',$item->service) }}</td>
                            <td>{{ $item->date ?? '' }} ({{ $item->schedule->from_time }} - {{ $item->schedule->to_time }})</td>
                            <td>{{ get_default_currency_symbol() }}{{ get_amount($item->price)  }}</td>
                            <td>
                                @if ($item->status == global_const()::PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT)
                                    <span>{{ __("Review Payment") }}</span> 
                                @elseif ($item->status == global_const()::PARLOUR_BOOKING_STATUS_PENDING)
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
                            <td><a href="{{ setRoute('user.my.booking.details',$item->slug)}}" class="btn btn--base btn--primary"><i class="fas fa-eye"></i></a></td>
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
</div>
@endsection
@push('script')
    
@endpush