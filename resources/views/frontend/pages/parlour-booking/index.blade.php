@php
    $app_local      = get_default_language_code();
@endphp     
@extends('frontend.layouts.master')

@push("css")
<style>
    .danger{
        background-color: #cca87643;
        border-radius: 10px;
        
    }
    .text--disable{
        color: #ffffffcf
    }
</style>
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
                                    @foreach (@$parlour->services as $item)
                                        <div class="service-item">
                                            <div class="service-inner">
                                                <input type="checkbox" name="service[]" value="{{ $item->service_name }}" class="hide-input service" id="service_{{ $item->id }}" data-item="{{ json_encode($item) }}">
                                                <label for="service_{{ $item->id }}" class="package--amount">
                                                    <p>{{ $item->service_name }} <span>{{ get_default_currency_symbol() }}{{ get_amount($item->price) }} </span></p>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="shedule-title pt-4">
                                <h3 class="title"><i class="fas fa-history"> </i> {{ __("Schedule") }}</h3>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="date-picker">
                                        <div class="row">
                                            <div class="col-xl-12 form-group">
                                                @php
                                                    $currentDate = \Carbon\Carbon::now();
                                                    $todaysDate     = \Carbon\Carbon::now()->format('d F, Y');
                                                    $schedule_date = $parlour->number_of_dates;
                                                @endphp
                                                <input type="hidden" class="todays-date" value="{{ $todaysDate }}">
                                                <label>{{ __("Select Date") }} <span>*</span></label>
                                                <select class="form--control nice-select date" name="date">
                                                    @for ($i = 0; $i < $schedule_date; $i++)
                                                        <option value="{{ $currentDate->format('d F, Y') }}">{{ $currentDate->format('d F, Y') }}</option>
                                                        @php
                                                            $currentDate->addDay();
                                                        @endphp
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $current_time       = now()->setTimeZone($basic_settings->timezone)->format('H:i');
                                @endphp
                                <input type="hidden" class="current_time" value="{{ $current_time }}">
                                <div class="col-lg-9">
                                    <div class="shedule-area">
                                        <label>{{ __("Select Time") }} <span>*</span></label>
                                        <div class="shedule-option" data-item="{{ json_encode($parlour->schedules) }}">
                                            
                                        </div>
                                    </div>
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
            $('.price').text('{{ get_default_currency_symbol() }}' + totalPrice.toFixed(2))
            $('#price').val(totalPrice.toFixed(2));
        });
        $(document).ready(function(){
            var selectedDate    = $('.date').val();
            var todaysDate      = $('.todays-date').val();      
            var currentTime     = $('.current_time').val();
            var data            = JSON.parse($('.shedule-option').attr("data-item"));
            run(selectedDate,todaysDate,currentTime,data);
            
        });
        $('.date').on('change',function(){
            var selectedDate    = $(this).val();
            var todaysDate      = $('.todays-date').val();      
            var currentTime     = $('.current_time').val();

            var data            = JSON.parse($('.shedule-option').attr("data-item"));
            $('.shedule-option').html('');
            
            run(selectedDate,todaysDate,currentTime,data);
            
        });
        function run(selectedDate,todaysDate,currentTime,data){
            $.each(data,function(index,item){
            var fromTime            = item.from_time;
            var disabled            = currentTime > fromTime ? 'disabled' : '';
            var disableClassName    = disabled === 'disabled' ? 'danger' : '';
            var textClass           = disabled === 'disabled' ? 'text--disable' : '';
            var itemData            = '';

                if(todaysDate == selectedDate){
                    itemData    += `
                    <div class="shedule-item">
                        <div class="shedule-inner ${disableClassName}">
                            <input type="radio" name="schedule" class="hide-input" value="${item.id}" id="shedule_${item.id}" ${disabled}>
                            <label for="shedule_${item.id}" class="package--amount">
                                <strong class="${textClass}">${item.from_time} - </strong> 
                                <strong class="${textClass}">${item.to_time}</strong>
                            </label>
                        </div>
                    </div>
                    `; 
                    $('.shedule-option').append(itemData);
                }else{
                    itemData    += `
                    <div class="shedule-item">
                        <div class="shedule-inner">
                            <input type="radio" name="schedule" class="hide-input" value="${item.id}" id="shedule_${item.id}">
                            <label for="shedule_${item.id}" class="package--amount">
                                <strong>${item.from_time} - </strong> 
                                <strong>${item.to_time}</strong>
                            </label>
                        </div>
                    </div>
                    `; 
                    $('.shedule-option').append(itemData);
                }
            
            });
        }
    </script>
@endpush