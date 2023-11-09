@php
    $default_lang_code   = language_const()::NOT_REMOVABLE;
    $system_default_lang = get_default_language_code();
    $languages_for_js_use = $languages->toJson();
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
    ], 'active' => __("Statistic Section")])
@endsection

@section('content')
    <div class="table-area mt-15">
        <div class="table-wrapper">
            <div class="table-header justify-content-end">
                <div class="table-btn-area">
                    <a href="#statistic-add" class="btn--base modal-btn"><i class="fas fa-plus me-1"></i> {{ __("Add Statistic") }}</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{ __("Title") }}</th>
                            <th>{{ __("Status") }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data->value->items ?? [] as $key => $item)
                            <tr data-item="{{ json_encode($item) }}">
                                <td>
                                    <ul class="user-list">
                                        <li><i class="{{ $item->icon ?? ""}}"></i></li>
                                    </ul>
                                </td>
                                <td> {{ $item->language->$system_default_lang->title ?? "" }} </td>
                                <td>
                                    @include('admin.components.form.switcher',[
                                        'name'        => 'statistic_status',
                                        'value'       => $item->status,
                                        'options'     => ['Enable' => 1, 'Disable' => 0],
                                        'onload'      => true,
                                        'data_target' => $item->id,
                                        'permission'  => "admin.setup.sections.statistic.status.update",
                                    ])
                                </td>
                                <td>
                                    <button class="btn btn--base edit-modal-button"><i class="las la-pencil-alt"></i></button>
                                    <button class="btn btn--base btn--danger delete-modal-button" ><i class="las la-trash-alt"></i></button>
                                </td>
                            </tr>
                        @empty
                            @include('admin.components.alerts.empty',['colspan' => 4])
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('admin.components.modals.site-section.statistic-section.add')

    @include('admin.components.modals.site-section.statistic-section.edit')
@endsection
@push('script')
    <script src="{{ asset('public/backend/js/fontawesome-iconpicker.js') }}"></script>
    <script>
        // icon picker
        $('.icp-auto').iconpicker();
    </script>
    <script>
        openModalWhenError("statistic-add","#statistic-add");
        openModalWhenError("statistic-edit","#statistic-edit");

        var default_language = "{{ $default_lang_code }}";
        var system_default_language = "{{ $system_default_lang }}";
        var languages = "{{ $languages_for_js_use }}";
        languages = JSON.parse(languages.replace(/&quot;/g,'"'));

        $(".edit-modal-button").click(function(){
            var oldData   = JSON.parse($(this).parents("tr").attr("data-item"));
            var editModal = $("#statistic-edit");

           

            editModal.find("form").first().find("input[name=target]").val(oldData.id);
            editModal.find("input[name="+default_language+"_title_edit]").val(oldData.language[default_language].title);
            editModal.find("input[name=counter_value_edit]").val(oldData.counter_value);
            editModal.find("input[name=icon_edit]").val(oldData.icon);
            
            $.each(languages,function(index,item){
                editModal.find("input[name="+item.code+"_title_edit]").val((oldData.language[item.code] == undefined ) ? '' : oldData.language[item.code].title);
            });
           
            editModal.find("input[name=image]").attr("data-preview-name",oldData.image);
            fileHolderPreviewReInit("#statistic-edit input[name=image]");
           
            openModalBySelector("#statistic-edit");

        });


        $(".delete-modal-button").click(function(){
            var oldData        = JSON.parse($(this).parents("tr").attr("data-item"));
            var actionRoute    = "{{ setRoute('admin.setup.sections.section.item.delete',$slug) }}";
            var target         = oldData.id;
            var message        = `Are you sure to <strong>delete</strong> this statistic?`;

            openDeleteModal(actionRoute,target,message);
        });
        
        $(document).ready(function(){
            // Switcher
            switcherAjax("{{ setRoute('admin.setup.sections.statistic.status.update',$slug) }}");
        })
        
    </script>
   
@endpush