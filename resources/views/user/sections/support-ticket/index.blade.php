@extends('user.layouts.master')

@push('css')
    
@endpush

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Profile"),
            'url'   => setRoute("user.profile.index"),
        ]
    ], 'active' => __("Support Tickets")])
@endsection

@section('content')
<div class="body-wrapper">
    <div class="table-area mt-10">
        <div class="table-wrapper">
            <div class="dashboard-header-wrapper">
                <h4 class="title">{{__("Support Tickets")}}</h4>
                <div class="dashboard-btn-wrapper">
                    <div class="dashboard-btn">
                        <a href="{{ route('user.support.ticket.create') }}" class="btn--base"><i class="las la-plus me-1"></i>{{ __("Add New") }}</a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __("Ticket ID") }}</th>
                            <th>{{ __("User") }} ({{ __("username") }}) </th>
                            <th>{{ __("Subject") }}</th>
                            <th>{{ __("Message") }}</th>
                            <th>{{ __("Status") }}</th>
                            <th>{{ __("Last Reply") }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($support_tickets as $item)
                            <tr>
                                <td>#{{ $item->token ?? "" }}</td>
                                <td> 
                                    <span class="text--info">{{ $item->user->username ?? "" }}</span>
                                </td>
                                <td>
                                    @if ($item->status == support_ticket_const()::DEFAULT)
                                    <span class="text--warning">{{ $item->subject }}</span>
                                    @elseif ($item->status == support_ticket_const()::SOLVED)
                                        <span class="text--success">{{ $item->subject }}</span>
                                    @elseif ($item->status == support_ticket_const()::ACTIVE)
                                        <span class="text--primary">{{ $item->subject }}</span>
                                    @elseif ($item->status == support_ticket_const()::PENDING)
                                        <span class="text--warning">{{ $item->subject }}</span>
                                    @endif
                                </td>
                                <td>{{ Str::words($item->desc , 5, '...') }}</td>
                                <td><span class="{{ $item->stringStatus->class }}">{{ $item->stringStatus->value }}</span></td>
                                <td>
                                    @if (count($item->conversations) > 0)
                                        {{ $item->conversations->last()->created_at->format("Y-m-d H:i A") ?? "" }}</td>
                                     @endif
                                </td>
                                <td><a href="{{ setRoute('user.support.ticket.conversation',encrypt($item->id)) }}" class="btn btn--base"><i class="las la-comment"></i></a></td>
                            </tr>
                        @empty
                            @include('user.components.alerts.empty',['colspan' => 8])
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
            {{ get_paginate($support_tickets)}}
        </div>
        <nav>
            <ul class="pagination">
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
                <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" rel="next" aria-label="Next &raquo;">&rsaquo;</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection

@push('script')
    <script>

    </script>
@endpush