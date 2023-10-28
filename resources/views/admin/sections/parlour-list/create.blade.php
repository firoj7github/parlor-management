@extends('admin.layouts.master')

@push('css')

    <style>
        .fileholder {
            min-height: 200px !important;
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
            'name'  => __("Parlour Lists"),
            'url'   => setRoute("admin.parlour.list.index")
        ]
    ], 'active' => __("Parlour Create")])
@endsection

@section('content')
<div class="custom-card">
    <div class="card-header">
        <h6 class="title">{{ __($page_title) }}</h6>
    </div>
    <div class="card-body">
        <form class="card-form" action="{{ setRoute('admin.parlour.list.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-4 form-group mb-5">
                    @include('admin.components.form.input-file',[
                        'label'             => __("Image"),
                        'name'              => "image",
                        'class'             => "file-holder",
                        'old_files'         => old("image"),
                        'attribute'         => 'data-height=130'
                    ])
                </div>
            </div>
            <div class="row justify-content-center mb-10-none">
                <div class="col-xl-6 col-lg-6 form-group">
                    <label>{{ __("Select Area") }}*</label>
                    <select class="form--control select2-basic" name="area">
                        <option disabled selected>{{ __("Select Area") }}</option>
                        @foreach ($areas as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    @include('admin.components.form.input',[
                        'label'             => __("Name")."*",
                        'name'              => "name",
                        'placeholder'       => __("Write Name")."...",
                        'value'             => old("name"),   
                    ])
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    @include('admin.components.form.input',[
                        'label'             => __("Manager Name")."*",
                        'name'              => "manager_name",
                        'placeholder'       => __("Write Manager Name")."...",
                        'value'             => old("manager_name"),   
                    ])
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    @include('admin.components.form.input',[
                        'label'             => __("Experience")."*",
                        'name'              => "experience",
                        'placeholder'       => __("Write Experience")."...",
                        'value'             => old("experience"),   
                    ])
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    @include('admin.components.form.input',[
                        'label'             => __("Speciality"),
                        'name'              => "speciality",
                        'placeholder'       => __("Write Speciality")."...",
                        'value'             => old("speciality"),   
                    ])
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    @include('admin.components.form.input',[
                        'label'             => __("Contact")."*",
                        'name'              => "contact",
                        'placeholder'       => __("Write Contact")."...",
                        'value'             => old("contact"),   
                    ])
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    @include('admin.components.form.input',[
                        'label'             => __("Address"),
                        'name'              => "address",
                        'placeholder'       => __("Write Address")."...",
                        'value'             => old("address"),   
                    ])
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    @include('admin.components.form.input',[
                        'label'             => __("Off Days")."*",
                        'name'              => "off_days",
                        'placeholder'       => __("Write Off Days")."...",
                        'value'             => old("off_days"),   
                    ])
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    @include('admin.components.form.input',[
                        'label'             => __("Number Of Dates")."*",
                        'name'              => "number_of_dates",
                        'placeholder'       => __("Number Of Dates")."...",
                        'value'             => old("number_of_dates"),   
                    ])
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    @include('admin.components.form.input',[
                        'label'             => __("Todays Date")."*",
                        'attribute'         => 'readonly',
                        'value'             => $todayDate,   
                    ])
                </div>
                <div class="col-xl-12 col-lg-12 form-group">
                    <div class="custom-inner-card">
                        <div class="card-inner-header">
                            <h6 class="title">{{ __("Service") }}</h6>
                            <button type="button" class="btn--base add-service-btn"><i class="fas fa-plus"></i> {{ __("Add") }}</button>
                        </div>
                        <div class="card-inner-body">
                            <div class="result">
                                @include('admin.components.parlour-list.service-item')    
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 form-group">
                    <div class="custom-inner-card">
                        <div class="card-inner-header">
                            <h6 class="title">{{ __("Schedule") }}</h6>
                            <button type="button" class="btn--base add-schedule-btn"><i class="fas fa-plus"></i> {{ __("Add") }}</button>
                        </div>
                        <div class="card-inner-body">
                            <div class="results">
                                @include('admin.components.parlour-list.schedule-item')    
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 form-group">
                    @include('admin.components.button.form-btn',[
                        'class'         => "w-100 btn-loading",
                        'text'          => "Submit",
                        'permission'    => "admin.parlour.list.store"
                    ])
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('script')
    <script>
        //getScheduleDays
        $(document).ready(function(){

            var getDayURL = "{{ setRoute('admin.parlour.list.get.days') }}";
            $('.add-schedule-btn').click(function(){
                $.get(getDayURL,function(data){
                    $('.results').prepend(data);
                    $('.results').find('.row').first().find("select").select2();
                });
            });
            var getServiceURL = "{{ setRoute('admin.parlour.list.get.service') }}";
            $('.add-service-btn').click(function(){
                $.get(getServiceURL,function(data){
                    $('.result').prepend(data);
                    $('.result').find('.row').first().find("select").select2();
                });
            });
        });
    </script>
@endpush