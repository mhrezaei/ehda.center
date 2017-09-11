<div class="price">
    @if($post->is_available)
        @if($post->isIt('IN_SALE'))
            <ins>{{ pd(number_format($post->sale_price)) }} {{ trans('front.toman') }}</ins>
            <del>{{ pd(number_format($post->price)) }} {{ trans('front.toman') }}</del>
        @else
            @if($post->price > 0)
                <ins>{{ pd(number_format($post->price)) }} {{ trans('front.toman') }}</ins>
            @endif
        @endif
        <div class="status">
            <div class="label green"> {{ trans('posts.form.is_available') }}</div>
        </div>
    @else
        <div class="status">
            <div class="label red"> {{ trans('posts.form.is_not_available') }}</div>
        </div>
    @endif
</div>