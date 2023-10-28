@forelse ($parlour_has_service ?? [] as $item)
    <div class="row align-items-end">
        <div class="col-xl-6 col-lg-6 form-group">
            @include('admin.components.form.input',[
                'label'         => __("Service Name")."*",
                'name'          => "service_name[]",
                'placeholder'   => __("Write Service Name")."...",
                'value'         => $item->service_name,
            ])
        </div>
        <div class="col-xl-5 col-lg-5 form-group">
            <label>{{ __("Price") }}*</label>
            <div class="input-group">
                <input type="text" class="form--control number-input" name="price[]" value="{{ $item->price }}" placeholder="{{ __("Enter Price") }}...">
                <span class="input-group-text">{{ get_default_currency_code($default_currency) }}</span>
            </div>
        </div>
        <div class="col-xl-1 col-lg-1 form-group">
            <button type="button" class="custom-btn btn--base btn--danger row-cross-btn w-100"><i class="las la-times"></i></button>
        </div>
    </div>
@empty
    <div class="row align-items-end">
        <div class="col-xl-6 col-lg-6 form-group">
            @include('admin.components.form.input',[
                'label'         => __("Service Name")."*",
                'placeholder'   => __("Write Service Name")."...",
                'name'          => "service_name[]",
            ])
        </div>
        <div class="col-xl-5 col-lg-5 form-group">
            <label>{{ __("Price") }}*</label>
            <div class="input-group">
                <input type="text" class="form--control number-input" name="price[]" placeholder="{{ __("Enter Price") }}...">
                <span class="input-group-text">{{ get_default_currency_code($default_currency) }}</span>
            </div>
        </div>
        <div class="col-xl-1 col-lg-1 form-group">
            <button type="button" class="custom-btn btn--base btn--danger row-cross-btn w-100"><i class="las la-times"></i></button>
        </div>
    </div>  
@endforelse