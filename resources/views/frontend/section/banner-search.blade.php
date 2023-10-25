<!-- banner-searching -->
<div class="banner-flotting-section">
    <div class="container">
        <div class="banner-flotting-item">
            <form class="banner-flotting-item-form" action="{{ setRoute('frontend.parlour.search') }}" method="GET">
                @csrf
                <div class="form-group">
                    @php
                        $area   = old('area');
                    @endphp
                    <select class="nice-select" name="area">
                        <option disabled selected>{{ __("Select Area") }}</option>
                        @foreach ($areas as $item)
                            <option value="{{ $item->id }}" @if ($item->id == @$areaString) selected
                                
                            @endif >{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group dr-name">
                    <input type="text" class="form--control" name="name" value="{{ @$nameString }}" placeholder="{{ __("Parlour Name") }}" spellcheck="false" data-ms-editor="true">
                    <i class="fas fa-user"></i>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn--base search-btn w-100"><i class="fas fa-search me-1"></i> {{ __("Search") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>