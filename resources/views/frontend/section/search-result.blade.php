<!-- serching data -->
<section class="parlour-list-area ptb-80">
    <div class="container">
        <div class="row justify-content-center">
            @foreach ($parlour_lists ?? [] as $item)
                <div class="col-lg-6 col-md-10 pb-20">
                    <div class="parlor-item">
                        <div class="parlor-img">
                            <img src="{{ get_image($item->image , 'site-section') ?? '' }}" alt="img">
                        </div>
                        <div class="parlor-details">
                            <h3 class="title">{{ $item->name ?? '' }}</h3>
                            <p>{{ $item->manager_name ?? '' }}</p>
                            <p>{{ $item->experience ?? '' }}</p>
                            <p>{{ $item->address ?? '' }}</p>
                            <div class="booking-btn">
                                <a href="{{ setRoute('frontend.get.service.index',$item->slug) }}" class="btn--base">{{ __("Get Service") }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>