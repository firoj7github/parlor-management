<div class="col-xl-12 col-lg-12 mb-10">
    <div class="custom-inner-card">
        <div class="card-inner-header">
            <h5 class="title">{{ __("Rate") }}</h5>
        </div>
        <div class="card-inner-body">
            <div class="row">
                <div class="col-12 form-group">
                    <label>{{ __("Rate") }}</label>
                    <div class="input-group">
                        <span class="input-group-text append ">1 &nbsp; <span class="default-currency text-white">{{ get_default_currency_code($default_currency) }}</span>&nbsp; = </span>
                        <input type="text" class="form--control number-input" value="{{ old("rate",0) }}" name="rate" placeholder="Type Here...">
                        <span class="input-group-text currency">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>