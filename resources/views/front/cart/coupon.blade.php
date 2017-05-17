<div class="coupon">
    <h5> {{ trans('cart.you_have_a_coupon?') }}</h5>
    <form action="#">
        <div class="field action">
            <input type="text" placeholder="{{ trans('cart.discount_code') }}">
            <button class="blue"> {{ trans('front.check_code') }} </button>
        </div>
        <div class="result">
            <div class="alert red"> {{ trans('cart.invalid_discount_code') }}</div>
        </div>
    </form>
</div>