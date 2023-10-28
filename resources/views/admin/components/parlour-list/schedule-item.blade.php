@forelse ($parlour_has_schedule ?? [] as $item)
    <div class="row align-items-end">
        <div class="col-xl-4 col-lg-4 form-group">
            @include('admin.components.form.input',[
                'label'         => __("From Time")."*",
                'type'          => 'time',
                'name'          => "from_time[]",
                'value'         => $item->from_time,
            ])
        </div>
        <div class="col-xl-4 col-lg-4 form-group">
            @include('admin.components.form.input',[
                'label'         => __("To Time")."*",
                'type'          => 'time',
                'name'          => "to_time[]",
                'value'         => $item->to_time,
            ])
        </div>
        <div class="col-xl-3 col-lg-3 form-group">
            @include('admin.components.form.input',[
                'label'             => __("Client")."(".__("Max").")"."*",
                'name'              => "max_client[]",
                'class'             => "number-input",
                'placeholder'       => "Write Here...",
                'value'             => $item->max_client,   
            ])
        </div>
        <div class="col-xl-1 col-lg-1 form-group">
            <button type="button" class="custom-btn btn--base btn--danger row-cross-btn w-100"><i class="las la-times"></i></button>
        </div>
    </div>
@empty
    <div class="row align-items-end">
        <div class="col-xl-4 col-lg-4 form-group">
            @include('admin.components.form.input',[
                'label'         => __("From Time")."*",
                'type'          => 'time',
                'name'          => "from_time[]",
            ])
        </div>
        <div class="col-xl-4 col-lg-4 form-group">
            @include('admin.components.form.input',[
                'label'         => __("To Time")."*",
                'type'          => 'time',
                'name'          => "to_time[]",
            ])
        </div>
        <div class="col-xl-3 col-lg-3 form-group">
            @include('admin.components.form.input',[
                'label'             => __("Client")."(".__("Max").")"."*",
                'name'              => "max_client[]",
                'class'             => "number-input",
                'placeholder'       => __("Write Here")."...",
            ])
        </div>
        <div class="col-xl-1 col-lg-1 form-group">
            <button type="button" class="custom-btn btn--base btn--danger row-cross-btn w-100"><i class="las la-times"></i></button>
        </div>
    </div>  
@endforelse

