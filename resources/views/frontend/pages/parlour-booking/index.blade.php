@php
    $app_local      = get_default_language_code();
@endphp     
@extends('frontend.layouts.master')

@push("css")
    
@endpush

@section('content') 
<section class="make-appointment pt-120 pb-60">
    <div class="container">
        <form action="{{ setRoute('parlour.booking.store') }}" method="post">
            @csrf
            <input type="hidden" name="parlour" value="{{ $parlour->slug }}">
            <input type="hidden" name="price" id="price">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="appointment-area">
                        <h3 class="title"><i class="fas fa-info-circle text--base mb-20"></i> {{ __("Make Appointment") }}</h3>
                        <div class="salon-thumb">
                            <img src="{{ get_image(@$parlour->image, 'site-section') }}" alt="img">
                        </div>
                        <div class="about-details">
                            <div class="salon-title">
                                <h3 class="title"><i class="las la-user-alt"></i> {{ ("Parlour Details") }}</h3>
                            </div>
                            <div class="list-wrapper">
                                <div class="preview-area">
                                    <div class="preview-item">
                                    <p>{{ __("Parlour Name") }} :</p>
                                    </div>
                                    <div class="preview-details">
                                        <p>{{ @$parlour->name ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="preview-area">
                                    <div class="preview-item">
                                    <p>{{ __("Parlour Address") }} :</p>
                                    </div>
                                    <div class="preview-details">
                                        <p>{{ @$parlour->address ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="preview-area">
                                    <div class="preview-item">
                                    <p>{{ __("Experience") }} :</p>
                                    </div>
                                    <div class="preview-details">
                                        <p>{{ @$parlour->experience ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="salon-title pt-4">
                                <h3 class="title"><i class="las la-street-view"></i> {{ __("Service Select") }}</h3>
                            </div>
                            <div class="service-select">
                                <div class="service-option pt-10">
                                    @foreach ($service_types as $item)
                                        <div class="service-item">
                                            <div class="service-inner">
                                                <input type="checkbox" name="service[]" value="{{ $item->name }}" class="hide-input service" id="service_{{ $item->id }}" data-item="{{ json_encode($item) }}">
                                                <label for="service_{{ $item->id }}" class="package--amount">
                                                    <p>{{ $item->name }} <span>{{ get_amount($item->price) }} {{ get_default_currency_symbol() }}</span></p>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
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
                            <div class="appointment-footer pt-5">
                                <div class="col-xl-12 col-lg-12 form-group">
                                    @include('admin.components.form.textarea',[
                                        'label'         => __("Message").'<span class="text--warning">'.'('.__("Optional").')'.'</span>',
                                        'name'          => 'message',
                                        'placeholder'   => __("Write Here")."...",
                                        'value'         => old("message")
                                    ])
                                </div>
                                <div class="col-lg-12 form-group pt-3">
                                    <button type="submit" class="btn--base small w-100">{{ __("Checkout") }} ( <span class="price">{{ get_default_currency_symbol()  }} </span> )<i class="fas fa-chevron-circle-right ms-1"></i></button>
                                </div>
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
        $('.service').on('change',function(){
            var servicePrice    = [];
            $('.service:checked').each(function(){
                var checkedPrice       = $(this).data('item');
                var price              = parseFloat(checkedPrice.price)
                servicePrice.push(price);
            });
            var totalPrice      = servicePrice.reduce(function (a,b) { 
                return a + b;
            },0);
            $('.price').text(totalPrice.toFixed(2) + ' ' + '{{ get_default_currency_symbol() }}')
            $('#price').val(totalPrice.toFixed(2));
        });
    </script>
@endpush