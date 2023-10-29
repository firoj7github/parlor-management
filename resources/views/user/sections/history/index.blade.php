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
    <div class="table-area mt-10">
        <div class="table-wrapper">
            <div class="dashboard-header-wrapper">
                <h4 class="title">{{ __("History List") }}</h4>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __("Parlour Name") }}</th>
                            <th>{{ __("Service") }}</th>
                            <th>{{ __("Schedule") }}</th>
                            <th>{{ __("Status") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data ?? [] as $item)
                        <tr>
                            <td>{{ $item->parlour->name ?? '' }}</td>
                            <td>{{ implode(', ',$item->service) }}</td>
                            <td>{{ $item->date ?? '' }} ({{ $item->schedule->from_time }} - {{ $item->schedule->to_time }})</td>
                            <td>
                                @if ($item->status == true)
                                    <span class="badge badge--success">{{ __("Booked") }}</span>  
                                @else
                                    <span class="badge badge--danger">{{ __("Pending") }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <div class="alert alert-primary text-center">
                                {{ __("No History Found!") }}
                            </div>
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