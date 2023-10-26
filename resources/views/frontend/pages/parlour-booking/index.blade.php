@php
    $app_local      = get_default_language_code();
@endphp     
@extends('frontend.layouts.master')

@push("css")
    
@endpush

@section('content') 

<section class="make-appointment pt-120 pb-60">
    <div class="container">
        <form class="booking-form" action="{{ setRoute('frontend.make.appointment.store') }}" method="POST">
            @csrf
            <input type="hidden" name="parlour" value="{{ $parlour->slug }}">
            <div class="appointment-title pb-40">
                <h2 class="title">{{ __("Make An Appointment") }}<i class="las la-arrow-right"></i></h2>
            </div>
            <div class="row">
                <div class="col-lg-6 pb-40">
                    <div class="salon-details">
                        <div class="salon-thumb">
                            <img src="{{ get_image($parlour?->image , 'site-section') }}" alt="img">
                        </div>
                        <div class="about-details">
                            <div class="salon-title">
                                <h3 class="title"><i class="las la-user-alt"></i>{{ @$parlour?->name ?? '' }}</h3>
                            </div>
                            <div class="details-area">
                                <div class="details-item">
                                    <div class="name-title">
                                        <p>{{ __("Manager") }} :</p>
                                    </div>
                                    <div class="details">
                                        <p>{{ @$parlour?->manager_name ?? '' }}</p>
                                    </div>
                                </div> 
                                <div class="details-item">
                                    <div class="name-title">
                                        <p>{{ __("Address") }} :</p>
                                    </div>
                                    <div class="details">
                                        <p>{{ @$parlour?->address ?? '' }}</p>
                                    </div>
                                </div> 
                                <div class="details-item">
                                    <div class="name-title">
                                        <p>{{ __("Experience") }} :</p>
                                    </div>
                                    <div class="details">
                                        <p>{{ @$parlour?->experience ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="details-item">
                                    <div class="name-title">
                                        <p>{{ __("Specialty") }} :</p>
                                    </div>
                                    <div class="details">
                                        <p>{{ @$parlour?->speciality ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="details-item">
                                    <div class="name-title">
                                        <p>{{ __("Price") }} :</p>
                                    </div>
                                    <div class="details">
                                        <p class="price"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="shedule-title pt-4">
                                <h3 class="title"><i class="las la-credit-card"></i>{{ __("Payment Method") }}</h3>
                            </div>
                            <div class="shedule-area">
                                <div class="shedule-option pt-10">
                                    @foreach ($payment_gateway as $item)
                                        <div class="shedule-item">
                                            <div class="shedule-inner">
                                                <input type="radio" name="payment_gateway" class="hide-input" value="{{ $item->id }}" id="payment_gateway_{{ $item->id }}">
                                                <label for="payment_gateway_{{ $item->id }}" class="package--amount">
                                                    <strong>{{ $item->name ?? '' }}</strong>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="salon-title pt-4">
                                <h3 class="title"><i class="las la-street-view"></i>{{ __("Salon Details") }}</h3>
                            </div>
                            <div class="details-area">
                                <div class="details-item">
                                    <div class="name-title">
                                        <p>{{ __("Contact") }} :</p>
                                    </div>
                                    <div class="details">
                                        <p>{{ @$parlour?->contact ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="details-item">
                                    <div class="name-title">
                                        <p>{{ __("Off Day") }} :</p>
                                    </div>
                                    <div class="details">
                                        <p>{{ @$parlour?->off_days ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="shedule-title pt-4">
                                <h3 class="title"><i class="fas fa-history"> </i>{{ __("Schedule") }}</h3>
                            </div>
                            <div class="shedule-area">
                                <div class="shedule-option pt-10">
                                    @foreach (@$parlour->schedules as $item)
                                        <div class="shedule-item">
                                            @php
                                                $from_time = @$item->from_time ?? '';
                                                $parsed_from_time = \Carbon\Carbon::createFromFormat('H:i', $from_time)->format('h A');

                                                $to_time   = @$item->to_time ?? '';
                                                $parsed_to_time = \Carbon\Carbon::createFromFormat('H:i', $to_time)->format('h A');
                                            @endphp
                                            <div class="shedule-inner">
                                                <input type="radio" name="schedule" class="hide-input" value="{{ $item->id }}" id="shedule_{{ $item->id }}">
                                                <label for="shedule_{{ $item->id }}" class="package--amount">
                                                    <strong>{{ $item->week->day ?? '' }}</strong>
                                                    <sup>{{ $parsed_from_time }} - </sup> 
                                                    <sup>{{ $parsed_to_time }}</sup>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="appointment-area">
                        <h3 class="title"><i class="fas fa-info-circle text--base mb-20"></i> {{ __("Appointment Form") }}</h3>
                        <div class="row justify-content-center mb-20-none">
                            <input type="hidden" name="price" class="form--control" id="price">
                            @if ($validated_user)
                                <div class="col-lg-6 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'        => __("Name").'<span class="text--base">*</span>',
                                        'name'         => 'name',
                                        'value'        => $validated_user->fullName,
                                        'attribute'    => "readonly",
                                        'placeholder'  => __("Enter Name").'...',
                                    ])
                                </div>
                                @if ($validated_user->full_mobile)
                                    @include('admin.components.form.input',[
                                        'label'        => __("Mobile").'<span class="text--warning">'.'('.__("Optional").')'.'</span>',
                                        'name'         => 'mobile',
                                        'value'        => $validated_user->full_mobile,
                                        'attribute'    => "readonly",
                                        'placeholder'  => __("Mobile").'...',
                                    ])
                                @else
                                    <div class="col-lg-6 col-md-6 form-group">
                                        @include('admin.components.form.input',[
                                            'label'        => __("Mobile").'<span class="text--warning">'.'('.__("Optional").')'.'</span>',
                                            'name'         => 'mobile',
                                            'value'        => old('mobile'),
                                            'placeholder'  => __("Mobile").'...',
                                        ])
                                    </div>
                                @endif
                                
                                <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'        => __("Email").'<span class="text--base">*</span>',
                                        'name'         => 'email',
                                        'value'        => $validated_user->email,
                                        'attribute'    => 'readonly',
                                        'placeholder'  => __("Email").'...'
                                    ])
                                </div>
                            @else
                                <div class="col-lg-6 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'        => __("Name").'<span class="text--base">*</span>',
                                        'name'         => 'name',
                                        'value'        => old('name'),
                                        'placeholder'  => __("Enter Name").'...',
                                    ])
                                </div>
                                <div class="col-lg-6 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'        => __("Mobile").'<span class="text--warning">'.'('.__("Optional").')'.'</span>',
                                        'name'         => 'mobile',
                                        'value'        => old('mobile'),
                                        'placeholder'  => __("Mobile").'...',
                                    ])
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'        => __("Email").'<span class="text--base">*</span>',
                                        'name'         => 'email',
                                        'value'        => old('email'),
                                        'placeholder'  => __("Email").'...'
                                    ])
                                </div>
                            @endif
                            <div class="col-lg-6 col-md-6 form-group">
                                <label>{{ __("Gender") }} <span class="text--base">*</span></label>
                                <select class="nice-select" name="gender">
                                    <option selected disabled>{{ __("Select Gender") }}</option>
                                    <option value="{{ global_const()::MALE }}">{{ global_const()::MALE }}</option>
                                    <option value="{{ global_const()::FEMALE }}">{{ global_const()::FEMALE }}</option>
                                    <option value="{{ global_const()::OTHERS }}">{{ global_const()::OTHERS }}</option>
                                </select>
                            </div>
                            <div class="col-lg-12 col-md-12 form-group">
                                <label>{{ __("Type") }} <span class="text--base">*</span></label>
                                <select name="type[]" class="form--control select2-auto-tokenize" id="selected-type" multiple="multiple" required>
                                    @foreach ($service_types as $item)
                                        <option value="{{ $item->name }}"
                                            data-item='{{ json_encode($item) }}'
                                            data-price='{{ $item->price }}'
                                            >{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.form.textarea',[
                                    'label'         => __("Message").'<span class="text--warning">'.'('.__("Optional").')'.'</span>',
                                    'name'          => 'message',
                                    'placeholder'   => __("Write Here")."...",
                                    'value'         => old("message")
                                ])
                            </div>
                            <div class="col-lg-12 form-group">
                                <button type="submit" class="btn--base small w-100">{{ __("Proceed") }} <i class="fas fa-chevron-circle-right ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@endsection
@push("script")
    <script>
        
        $('#selected-type').on('change',function () { 
            var selectedAmount    = [];
            $('#selected-type option:selected').each(function() {
                var selectedType    = ($(this).data('item'));
                var price           = parseFloat(selectedType.price);
                selectedAmount.push(price);
            });
           
            var totalPrice = selectedAmount.reduce(function(a, b) {
                return a + b;
            }, 0);

            $('.price').text(totalPrice.toFixed(2) + ' ' + '{{ get_default_currency_code() }}');
            $('#price').val(totalPrice);
        });
    </script>
@endpush