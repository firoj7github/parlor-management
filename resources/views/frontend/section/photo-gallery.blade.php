@php
    $app_local      = get_default_language_code();
@endphp
<!-- photo galary -->

<section class="photo-section ptb-60">
    <div class="container">
        <div class="photo-section-title pb-60">
            <div class="row">
                <div class="col-lg-7">
                    <h4 class="title text--base pb-20">{{ $photo_gallery->value->language->$app_local->title ?? '' }}</h4>
                    <h2 class="title">{{ $photo_gallery->value->language->$app_local->heading ?? '' }}</h2>
                    <p>{{ $photo_gallery->value->language->$app_local->sub_heading ?? '' }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="galary-area-1">
                    <div class="row">
                        @foreach (array_slice((array)$photo_gallery?->value?->items,0,2) as $item)
                            <div class="col-lg-12 pb-20">
                                <div class="photo-item">
                                    {{-- <img src="{{ get_image($item->image,'site-section') }}" alt="img"> --}}
                                    <img src="{{ asset('frontend/images/site-section/' . $item->image) }}" alt="client">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 pb-20">
                @foreach (array_slice((array)$photo_gallery?->value?->items,2,1) as $item)
                    <div class="galary-area-2">
                        <div class="photo-item">
                            {{-- <img src="{{ get_image($item->image,'site-section') }}" alt="img"> --}}
                            <img src="{{ asset('frontend/images/site-section/' . $item->image) }}" alt="client">
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 pb-20">
                <div class="galary-area-1">
                    <div class="row">
                        @foreach (array_slice((array)$photo_gallery?->value?->items,3,2) as $item)
                            <div class="col-lg-12 pb-20">
                                <div class="photo-item">
                                    {{-- <img src="{{ get_image($item->image,'site-section') }}" alt="img"> --}}
                                    <img src="{{ asset('frontend/images/site-section/' . $item->image) }}" alt="client">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @foreach (array_slice((array)$photo_gallery?->value?->items,5) as $item)
                <div class="col-lg-6 col-md-6 col-sm-6 pb-20">
                    <div class="galary-area-3">
                        <div class="photo-item">
                            {{-- <img src="{{ get_image($item->image,'site-section') }}" alt="img"> --}}
                            <img src="{{ asset('frontend/images/site-section/' . $item->image) }}" alt="client">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
