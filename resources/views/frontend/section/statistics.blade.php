@php
    $app_local      = get_default_language_code();
@endphp
<!-- statistics-section -->
<section class="statistics-section ptb-60">
    <div class="container">
        <div class="row text-center">
            @foreach ($statistic?->value?->items ?? [] as $item)
                @if ($item->status == 1)
                    <div class="col-lg-6 col-md-6 col-sm-6 pb-20">
                        <div class="counter">
                            <i class="{{ $item->icon ?? '' }}"></i>
                            <div class="odo-area">
                                <h2 class="odo-title odometer" data-odometer-final="{{ $item->counter_value ?? '' }}">0</h2>
                            </div>
                            <h4 class="title">{{ $item->language->$app_local->title ?? '' }}</h4>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>