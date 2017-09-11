@php
    $post->spreadMeta();
    $title = ad($post->title)
@endphp
<div class="col-sm-4 filterable"
     data-category="{{ implode(',', $post->categories->pluck('slug')->toArray()) }}"
     data-available="{{ ((int) $post->is_available) }}"
     data-special-sale="{{ ((int) $post->isIt('IN_SALE')) }}"
     data-title="{{ $title }}"
     data-price="{{ $post->current_price }}">
    {{--<a href="#" class="product-item ribbon-new ribbon-sale">--}}
    <a href="{{ $post->direct_url }}"
       class="product-item @if($post->isIt('NEW')) ribbon-new @endif @if($post->isIt('IN_SALE')) ribbon-sale @endif">
        <div class="thumbnail"><img src="{{ url($post->viewable_featured_image) }}">
        </div>
        <div class="content">
            <h6> {{ $title }} </h6>
            <div class="price">
                @if($post->isIt('AVAILABLE'))
                    @if($post->isIt('IN_SALE'))
                        <del>
                            {{ ad(number_format($post->price)) }}
                        </del>
                        <ins>
                            {{ ad(number_format($post->sale_price)) }}
                            {{ trans('front.toman') }}
                        </ins>
                    @else
                        @if($post->price > 0)
                            <ins>
                                {{ ad(number_format($post->price)) }}
                                {{ trans('front.toman') }}
                            </ins>
                        @endif
                    @endif
                @else
                    <div class="status">
                        <div class="label red"> {{ trans('posts.form.is_not_available') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </a>
</div>