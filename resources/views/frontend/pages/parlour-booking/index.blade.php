@php
    $app_local      = get_default_language_code();
@endphp     
@extends('frontend.layouts.master')

@push("css")
    
@endpush

@section('content') 

<section class="make-appointment pt-120 pb-60">
    <div class="container">
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
                            <h3 class="title"><i class="las la-user-alt"></i>{{ $parlour?->name ?? '' }}</h3>
                        </div>
                        <div class="details-area">
                            <div class="details-item">
                                <div class="name-title">
                                    <p>{{ __("Manager") }} :</p>
                                </div>
                                 <div class="details">
                                     <p>{{ $parlour?->manager_name ?? '' }}</p>
                                 </div>
                            </div> 
                            <div class="details-item">
                                <div class="name-title">
                                    <p>{{ __("Address") }} :</p>
                                </div>
                                 <div class="details">
                                     <p>{{ $parlour?->address ?? '' }}</p>
                                 </div>
                            </div> 
                            <div class="details-item">
                                <div class="name-title">
                                    <p>{{ __("Experience") }} :</p>
                                </div>
                                 <div class="details">
                                     <p>{{ $parlour?->experience ?? '' }}</p>
                                 </div>
                            </div>
                            <div class="details-item">
                                <div class="name-title">
                                    <p>{{ __("Specialty") }} :</p>
                                </div>
                                 <div class="details">
                                     <p>{{ $parlour?->speciality ?? '' }}</p>
                                 </div>
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
                                     <p>{{ $parlour?->contact ?? '' }}</p>
                                 </div>
                            </div>
                            <div class="details-item">
                                <div class="name-title">
                                    <p>{{ __("Off Day") }} :</p>
                                </div>
                                 <div class="details">
                                     <p>{{ $parlour?->off_days ?? '' }}</p>
                                 </div>
                            </div>
                        </div>
                        <div class="shedule-title pt-4">
                            <h3 class="title"><i class="fas fa-history"> </i>{{ __("Schedule") }}</h3>
                        </div>
                        <div class="shedule-area">
                            <div class="shedule-option pt-10">
                                @foreach ($parlour->schedules as $item)
                                    <div class="shedule-item">
                                        @php
                                            $from_time = $item->from_time ?? '';
                                            $parsed_from_time = \Carbon\Carbon::createFromFormat('H:i', $from_time)->format('h A');

                                            $to_time   = $item->to_time ?? '';
                                            $parsed_to_time = \Carbon\Carbon::createFromFormat('H:i', $to_time)->format('h A');
                                        @endphp
                                        <div class="shedule-inner">
                                            <input type="radio" name="shedule" class="hide-input" id="shedule_{{ $item->id }}">
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
                    <form class="booking-form" action="appointment.preview.html">
                        <h3 class="title"><i class="fas fa-info-circle text--base mb-20"></i> Appointment Form</h3>
                        <div class="row justify-content-center mb-20-none">
                            <div class="col-lg-6 col-md-6 form-group">
                                <label> Name <span>*</span></label>
                                <input type="text" name="text" class="form--control" placeholder="Enter Name...">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label>Mobile <small class="text--warning">(optional)</small></label>
                                <input type="number" name="number" class="form--control" placeholder="Mobile...">
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                <label>Email <span>*</span></label>
                                <input type="email" name="email" class="form--control" placeholder="Email...">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label>Gender <span class="text--base">*</span></label>
                                    <select class="nice-select">
                                        <option value="1">Select</option>
                                        <option value="2">Male</option>
                                        <option value="3">Female</option>
                                        <option value="4">Other</option>
                                    </select>
                            </div>
                            <div class="col-lg-12 col-md-12 form-group">
                                <label>Type <span class="text--base">*</span></label>
                                <select name="keywords[]" class="form--control select2-auto-tokenize"  multiple="multiple" required>
                                    <option value="1">Short Haircut </option>
                                    <option value="2">Long Haircut</option>
                                    <option value="3">Hair Wash</option>
                                    <option value="4">Hair Color</option>
                                    <option value="5">Facial Massage </option>
                                    <option value="6">Shaves Face</option>
                                    <option value="7">Spa & Health</option>
                                    <option value="8">Lamination</option>
                                </select>
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                <label>Your Message <small class="text--warning">(optional)</small></label>
                                <textarea class="form--control" placeholder="Write Here..."></textarea>
                            </div>
                            <div class="col-lg-12 form-group">
                                <button type="submit" class="btn--base small w-100">Proceed <i class="fas fa-chevron-circle-right ms-1"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@push("script")
    
@endpush