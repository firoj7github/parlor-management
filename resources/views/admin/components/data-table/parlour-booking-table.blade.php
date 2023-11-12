<table class="custom-table booking-search-table">
    <thead>
        <tr>
            <th>{{ __("Booking ID") }}</th>
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