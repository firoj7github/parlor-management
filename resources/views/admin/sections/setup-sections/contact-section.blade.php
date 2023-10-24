@php
    $default_lang_code = language_const()::NOT_REMOVABLE;
    $system_default_lang = get_default_language_code();
    $languages_for_js_use = $languages->toJson();
@endphp

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
    ], 'active' => __("Contact Section")])
@endsection

@section('content')
    <div class="custom-card">
        <div class="card-header">
            <h6 class="title">{{ __("Contact Section") }}</h6>
        </div>
        <div class="card-body">
            <form class="card-form" action="{{ setRoute('admin.setup.sections.section.update',$slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row justify-content-center mb-10-none">
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input-file',[
                            'label'             => __("Image"),
                            'name'              => "image",
                            'class'             => "file-holder",
                            'old_files_path'    => files_asset_path("site-section"),
                            'old_files'         => $data->value->image ?? "",
                        ])
                    </div>
                    <div class="col-xl-12 col-lg-12">
                        <div class="product-tab">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    @foreach ($languages as $item)
                                        <button class="nav-link @if (get_default_language_code() == $item->code) active @endif" id="{{$item->name}}-tab" data-bs-toggle="tab" data-bs-target="#{{$item->name}}" type="button" role="tab" aria-controls="{{ $item->name }}" aria-selected="true">{{ $item->name }}</button>
                                    @endforeach
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                @foreach ($languages as $item)
                                    @php
                                        $lang_code = $item->code;
                                    @endphp
                                    <div class="tab-pane @if (get_default_language_code() == $item->code) fade show active @endif" id="{{ $item->name }}" role="tabpanel" aria-labelledby="english-tab">
                                        <div class="form-group">
                                            @include('admin.components.form.input',[
                                                'label'         => "Section Title<span>*<span>",
                                                'name'          => $item->code . "_title",
                                                'value'         => old($item->code . "_title",$data->value->language->$lang_code->title ?? "")
                                            ])
                                        </div>
                                        <div class="form-group">
                                            @include('admin.components.form.input',[
                                                'label'         => "Description<span>*<span>",
                                                'name'          => $item->code . "_description",
                                                'value'         => old($item->code . "_description",$data->value->language->$lang_code->description ?? "")
                                            ])
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="border-bottom my-3"></div>
                    <div class="form-group">
                        @include('admin.components.form.input',[
                            'label'         => "Phone<span>*<span>",
                            'name'          => "phone",
                            'value'         => old("phone",$data->value->phone ?? "")
                        ])
                    </div>
                    <div class="form-group">
                        @include('admin.components.form.input',[
                            'label'         => "Address<span>*<span>",
                            'name'          => "address",
                            'value'         => old("address",$data->value->address ?? "")
                        ])
                    </div>    
                    <div class="form-group">
                        @include('admin.components.form.input',[
                            'label'         => "Email<span>*<span>",
                            'name'          => "email",
                            'value'         => old("email",$data->value->email ?? "")
                        ])
                    </div> 
                    <div class="col-xl-12 col-lg-12 form-group">
                        <div class="custom-inner-card input-field-generator" data-source="setup_section_contact_schedule_input">
                            <div class="card-inner-header">
                                <h6 class="title">{{ __("Schedule") }}</h6>
                                <button type="button" class="btn--base add-row-btn"><i class="fas fa-plus"></i> {{ __("Add") }}</button>
                            </div>
                            <div class="card-inner-body">
                                <div class="results">
                                    @php
                                        $schedule = $data->value->schedules ?? [];
                                    @endphp
                                    @forelse ($schedule as $item)
                                        <div class="row align-items-end">
                                            
                                            <div class="col-xl-11 col-lg-11 form-group">
                                                @include('admin.components.form.input',[
                                                    'label'         => "Schedule*",
                                                    'name'          => "schedule[]",
                                                    'value'         => $item->schedule ?? "",
                                                ])
                                            </div>
                                            <div class="col-xl-1 col-lg-1 form-group">
                                                <button type="button" class="custom-btn btn--base btn--danger row-cross-btn w-100"><i class="las la-times"></i></button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="row align-items-end">
                                            
                                            <div class="col-xl-11 col-lg-11 form-group">
                                                @include('admin.components.form.input',[
                                                    'label'         => "Schedule*",
                                                    'name'          => "schedule[]",
                                                ])
                                            </div>
                                            <div class="col-xl-1 col-lg-1 form-group">
                                                <button type="button" class="custom-btn btn--base btn--danger row-cross-btn w-100"><i class="las la-times"></i></button>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.button.form-btn',[
                            'class'         => "w-100 btn-loading",
                            'text'          => "Submit",
                            'permission'    => "admin.setup.sections.section.update"
                        ])
                    </div>
                    
                   
                </form>
            </div>
        </div>
    </div>
@endsection
