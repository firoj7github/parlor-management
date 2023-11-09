@php
    $app_local = get_default_language_code();
@endphp

@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('public/backend/css/fontawesome-iconpicker.css') }}">
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
    ], 'active' => __("Blog Categories")])
@endsection

@section('content')
    <div class="table-area mt-15">
        <div class="table-wrapper">
            <div class="table-header justify-content-end">
                <div class="table-btn-area">
                    <a href="#category-add" class="btn--base modal-btn"><i class="fas fa-plus me-1"></i> {{ __("Add Category") }}</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                        
                            <th>{{ __("Name") }}</th>
                            <th>{{ __("Status") }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories ?? [] as $key => $item)
                            <tr data-item="{{ $item }}">
                                <td>{{ $item->name->language->$app_local->name ?? ''}}</td>
                                <td>
                                    @include('admin.components.form.switcher',[
                                        'name'        => 'status',
                                        'value'       => $item->status,
                                        'options'     => ['Enable' => 1, 'Disable' => 0],
                                        'onload'      => true,
                                        'data_target' => $item->id,
                                    ])
                                </td>
                                <td>
                                    <button class="btn btn--base btn--danger delete-modal-button" ><i class="las la-trash-alt"></i></button>
                                </td>
                            </tr>
                        @empty
                            @include('admin.components.alerts.empty',['colspan' => 3])
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="category-add" class="mfp-hide large">
        <div class="modal-data">
            <div class="modal-header px-0">
                <h5 class="modal-title">{{ __("Add Blog Category") }}</h5>
            </div>
            <div class="modal-form-data">
                <form class="card-form" action="{{ setRoute('admin.setup.sections.category.store')}}" method="POST">
                    @csrf
                    <div class="row mb-10-none">
                        <div class="language-tab">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    @foreach ($languages as $item)
                                        <button class="nav-link @if (get_default_language_code() == $item->code) active @endif" id="modal-{{$item->name}}-tab" data-bs-toggle="tab" data-bs-target="#modal-{{$item->name}}" type="button" role="tab" aria-controls="modal-{{ $item->name }}" aria-selected="true">{{ $item->name }}</button>
                                    @endforeach
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                @foreach ($languages as $item)
                                    @php
                                        $lang_code = $item->code;
                                    @endphp
                                    <div class="tab-pane @if (get_default_language_code() == $item->code) fade show active @endif" id="modal-{{ $item->name }}" role="tabpanel" aria-labelledby="modal-{{$item->name}}-tab">
                                        <div class="col-xl-12 col-lg-12 form-group">
                                            @include('admin.components.form.input',[
                                                'label'         => "Name*",
                                                'name'          => $item->code . "_name",
                                                'data_limit'    => 150,
                                                'placeholder'   => "Write Name...",
                                                'value'         => old($lang_code . "_name"),
                                            ])
                                        </div>
                                    </div>
                                @endforeach
                            </div> 
                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.button.form-btn',[
                                    'class'         => "w-100 btn-loading",
                                    'permission'    => "admin.setup.sections.category.store",
                                    'text'          => "Add",
                                ])
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')

    <script>
        openModalWhenError("category-add","#category-add");
        
        $(".delete-modal-button").click(function (){
            var oldData     = JSON.parse($(this).parents("tr").attr("data-item"));
            var actionRoute = "{{ setRoute('admin.setup.sections.category.delete') }}";
            var target      = oldData.id;
            
            var message     = `Are you sure to <span>delete</span> this category?`;
            openDeleteModal(actionRoute,target,message);
        });
        
        $(document).ready(function(){
            switcherAjax("{{ setRoute('admin.setup.sections.category.status.update') }}");
        })
    </script>
   
@endpush