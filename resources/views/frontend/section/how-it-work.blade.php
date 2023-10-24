@php
    $app_local      = get_default_language_code();
@endphp
<!--  how its work -->
<div class="how-its-work-section ptb-60">
    <div class="container">
        <div class="how-its-work-title pb-60">
            <h4 class="titte text--base pb-20">{{ $how_its_work->value->language->$app_local->title ?? '' }}</h4>
            <h2 class="titte d-flex align-items-center">{{ $how_its_work->value->language->$app_local->heading ?? '' }}<i class="las la-arrow-right"></i></h2>
            <p>{{ $how_its_work->value->language->$app_local->sub_heading ?? '' }}</p>
        </div>
        <div class="row">
            @if (isset($how_its_work->value->items))
                @php
                    $step_key = 0;
                    $how_its_works = $how_its_work->value->items ?? [];
                @endphp
                @foreach ($how_its_works as $key => $item)
                    @php
                        $step_key++;
                    @endphp
                    <div class="col-xl-3 col-lg-4 col-md-6 pb-20">
                        <div class="work-steps">
                            <div class="icon">
                                <i class="{{ $item->icon ?? '' }}"></i>
                            </div>
                            <div class="step-content">
                                <p>{{ __("Step") }} {{ $step_key }}</p>
                                <h3>{{ $item->language->$app_local->item_title ?? '' }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>