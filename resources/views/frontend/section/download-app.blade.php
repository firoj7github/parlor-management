@php
    $app_local      = get_default_language_code();
@endphp
<!-- app section -->
<section class="app-section ptb-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 pb-40">
                <div class="app-img">
                    <img src="{{ get_image($download_app?->value?->image , 'site-section') }}" alt="img">
                </div>
            </div>
            <div class="col-xl-7 col-lg-6 mb-30">
                <div class="app-content">
                    <span class="sub-title text--base mb-20">{{ $download_app->value->language->$app_local->title ?? '' }}</span>
                    <h2 class="title">{{ $download_app->value->language->$app_local->heading ?? '' }}</h2>
                    <p>{{ $download_app->value->language->$app_local->sub_heading ?? '' }}</p>
                    <div class="app-btn-wrapper">
                        @foreach ($download_app?->value?->items ?? [] as $item)
                        <a href="#0" class="app-btn">
                            <div class="content">
                                <span>{{ $item->language->$app_local->item_title ?? '' }}</span>
                                <h5 class="title">{{ $item->language->$app_local->item_heading ?? '' }}</h5>
                            </div>
                            <div class="icon">
                                <img src="{{ get_image($item->image , 'site-section') }}" alt="element">
                            </div>
                            <div class="app-qr">
                                <img src="{{ get_image($item->image , 'site-section') }}" alt="element">
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>