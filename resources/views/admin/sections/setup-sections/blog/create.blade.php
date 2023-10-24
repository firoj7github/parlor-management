@php
    $app_local = get_default_language_code();
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
        ],
        [
            'name'  => __("Web Blog Section"),
            'url'   => setRoute('admin.setup.sections.section','blog')  
        ]
    ], 'active' => __("New Blog Create")])
@endsection
@section('content')
    <div class="custom-card">
        <div class="card-header">
            <h6 class="title">{{ __($page_title) }}</h6>
        </div>
        <div class="card-body">
            <form class="card-form" action="{{ setRoute('admin.setup.sections.blog.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row justify-content-center mb-10-none">
                    <div class="col-xl-4 col-lg-4 form-group">
                        @include('admin.components.form.input-file',[
                            'label'             => "Image:",
                            'name'              => "image",
                            'class'             => "file-holder",
                            'old_files_path'    => files_asset_path("site-section"),
                            'old_files'         => old("old_image"),
                        ])
                    </div>
                    
                    <div class="col-xl-8 col-lg-8">
                        
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
                                                'label'         => "Title",
                                                'label_after'   => "*",
                                                'name'          => $item->code . "_title",
                                                'value'         => old($item->code . "_title")
                                            ])
                                        </div>
                                        <div class="form-group">
                                            @include('admin.components.form.input-text-rich',[
                                                'label'         => "Description",
                                                'label_after'   => "*",
                                                'name'          => $item->code . "_description",
                                                'value'         => old($item->code . "_description")
                                            ])
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __("Tags") }}<span>*</span></label>
                                            <select name="{{ $item->code }}_tags[]" class="form-control select2-auto-tokenize"  multiple="multiple" data-placeholder="Add Tags"></select>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="form-group">
                                    <label>{{ __("Select Category*") }}</label>
                                    <select class="form--control select2-basic" name="category">
                                        <option disabled selected>Select Category</option>
                                        @foreach ($category as $data)
                                            <option value="{{ $data->id }}">{{ $data->name->language->$app_local->name }}</option>
                                        @endforeach
                                    </select>
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
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')

@endpush