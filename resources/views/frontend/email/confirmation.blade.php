<!DOCTYPE html>
<html>
<head>
  <style>
    /* Add some basic styling to the table */
    table {
      border-collapse: collapse;
      width: 100%;
      border-radius: 10px;
      box-shadow: rgba(0, 0, 0, 0.15) 0px 3px 7px 0px;
      border: 1px solid #eee;
      overflow: hidden;
    }

    th, td {
      border: 1px solid #eee;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>

    
<p>Dear {{ $user->fullname }},</p>

<p>We are writing to provide you with comprehensive details regarding your recent booking with the MTCN number: {{ $trx_id }}. Ensuring transparency and clarity in our communication is paramount, and we are pleased to share the following information with you:</p>

<h5>Parlour Summary</h5>
<table>
  <tr>
    <td>Parlour Name</td>
    <td>{{ $parlour_data->name }}</td>
    <td>Experience</td>
    <td>{{ $parlour_data->experience }}</td>
  </tr>
  <tr>
    <td>Contact</td>
    <td>{{ $parlour_data->contact }}</td>
    <td>Address</td>
    <td>{{ $parlour_data->address }}</td>
  </tr>
</table>

<h5>Service & Schedule Information</h5>

<table>
  <tr>
    <td>Service</td>
    <td>{{ implode(', ',$user_data->service) }} </td>
    <td>Date</td>
    <td>{{ $user_data->date }} </td>
  </tr>
  <tr>
    <td>Time</td>
    <td>{{ $schedule_data->from_time }} - {{ $schedule_data->to_time }}</td>
    <td>Serial Number</td>
    <td>{{ $user_data->serial_number }} </td>
  </tr>
  <tr>
    <td>Status</td>
    <td>
      @if ($user_data->status == global_const()::PARLOUR_BOOKING_STATUS_PENDING)
          <span>{{ __("Pending") }}</span>
      @elseif ($user_data->status == global_const()::PARLOUR_BOOKING_STATUS_CONFIRM_PAYMENT)
          <span>{{ __("Confirm Payment") }}</span>
      @elseif ($user_data->status == global_const()::PARLOUR_BOOKING_STATUS_HOLD)
          <span>{{ __("On Hold") }}</span>
      @elseif ($user_data->status == global_const()::PARLOUR_BOOKING_STATUS_SETTLED)
          <span>{{ __("Settled") }}</span>
      @elseif ($user_data->status == global_const()::PARLOUR_BOOKING_STATUS_COMPLETE)
          <span>{{ __("Completed") }}</span>
      @elseif ($user_data->status == global_const()::PARLOUR_BOOKING_STATUS_CANCEL)
          <span>{{ __("Canceled") }}</span>
      @elseif ($user_data->status == global_const()::PARLOUR_BOOKING_STATUS_FAILED)
          <span>{{ __("Failed") }}</span>
      @elseif ($user_data->status == global_const()::PARLOUR_BOOKING_STATUS_REFUND)
          <span>{{ __("Refunded") }}</span>
      @else
          <span>{{ __("Delayed") }}</span>
      @endif
    </td>
  </tr>
</table>

<h5>Payment Information</h5>

<table>
  <tr>
    <td>Payment Method</td>
    <td>{{ $user_data->payment_method }} </td>
    <td>Service Price</td>
    <td>{{ get_default_currency_symbol() }}{{ getAmount($user_data->price) }} </td>
  </tr>
  <tr>
    <td>Fees & Charges</td>
    <td>{{ get_default_currency_symbol() }}{{ getAmount($user_data->total_charge) }}</td>
    <td>Total Payable Price</td>
    <td>{{ get_default_currency_symbol() }}{{ getAmount($user_data->payable_price) }} </td>
  </tr>
</table>



<p>We believe that providing these detailed breakdowns will give you a clear understanding of the remittance process and the associated particulars. Should you have any questions, require further assistance, or notice any discrepancies, please do not hesitate to reach out to our dedicated support team at {{ $contact->value->email }}.</p>

<p>Your satisfaction and trust are of utmost importance to us, and we are committed to ensuring a seamless and secure remittance experience for you. Thank you for choosing us as your trusted partner for your financial needs.</p>
<p>Best Regards</p>
<p>{{ $basic_settings->site_name }}</p>

</body>
</html>
