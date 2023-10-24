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
    ], 'active' => __("Contact Messages")])
@endsection
@section('content')
    <div class="table-area">
        <div class="table-wrapper">
            <div class="table-header">
                <h5 class="title">{{ __($page_title) }}</h5>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Reply</th>
                            <th>Created At</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contact_requests ?? [] as $key => $item)
                            <tr data-item="{{ json_encode($item->only(['id'])) }}">
                                <td>{{ $key + $contact_requests->firstItem() }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->message }}</td>
                                <td>
                                    @if ($item->reply == true)
                                        <span class="badge badge--success">{{ __("Replyed") }}</span>
                                    @else
                                        <span class="badge badge--warning">{{ __("Not Replyed") }}</span>
                                    @endif
                                </td>
                                <td>{{ $item->created_at->format("d-m-Y H:i:s") }}</td>
                                <td>
                                    @include('admin.components.link.custom',[
                                        'href'          => "#send-reply",
                                        'class'         => "btn btn--base reply-button modal-btn",
                                        'icon'          => "las la-reply-all",
                                        'permission'    => "admin.contact.messages.reply",
                                    ])
                                </td>
                            </tr>
                        @empty
                            @include('admin.components.alerts.empty',['colspan' => 7])
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{ get_paginate($contact_requests) }}
    </div>
    {{-- Send Mail Modal --}}
    @if (admin_permission_by_name("admin.contact.messages.reply"))
        <div id="send-reply" class="mfp-hide large">
            <div class="modal-data">
                <div class="modal-header px-0">
                    <h5 class="modal-title">{{ __("Send Reply") }}</h5>
                </div>
                <div class="modal-form-data">
                    <form class="card-form" action="{{ setRoute('admin.contact.messages.reply') }}" method="POST">
                        @csrf
                        <input type="hidden" name="target" value="{{ old('target') }}">
                        <div class="row mb-10-none">
                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.form.input',[
                                    'label'         => "Subject*",
                                    'name'          => "subject",
                                    'data_limit'    => 150,
                                    'placeholder'   => "Write Subject...",
                                    'value'         => old('subject'),
                                ])
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.form.input-text-rich',[
                                    'label'         => "Details*",
                                    'name'          => "message",
                                    'value'         => old('message'),
                                ])
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.button.form-btn',[
                                    'class'         => "w-100 btn-loading",
                                    'permission'    => "admin.subscriber.send.mail",
                                    'text'          => "Send Email",
                                ])
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('script')
    <script>
        openModalWhenError("send-reply","#send-reply");
        $(".reply-button").click(function(){
            var oldData = JSON.parse($(this).parents("tr").attr("data-item"));
            $("#send-reply").find("input[name=target]").val(oldData.id);
        });
    </script>
@endpush