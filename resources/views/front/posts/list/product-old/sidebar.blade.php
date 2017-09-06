{{ null, $ajaxFilter = true }}
@if($ajaxFilter)
    @include($viewFolder . '.ajax-filter-assets')
@else
    @include($viewFolder . '.filter-assets')
@endif

<div class="col-sm-3">
    <div class="category-filters filters-panel" data-filter-url="{{ url(getLocale() . '/products/filter') }}">
        <div class="title"> {{ trans('posts.filters.filters') }}</div>
        <article>
            <div class="field filter-text" data-identifier="title">
                <label for="product-name"> {{ trans('validation.attributes.title') }} </label>
                <input type="text" id="product-name">
            </div>
            {{ null, $minPrice = PostsServiceProvider::productsMinPrice($allPosts) }}
            {{ null, $maxPrice = PostsServiceProvider::productsMaxPrice($allPosts) }}
            @if($minPrice != $maxPrice)
                <hr class="small">
                <div class="field filter-slider" data-identifier="price">
                    <label>
                        {{ trans('validation.attributes.price') }}
                        (
                        {{ trans('posts.filters.range_from') }}
                        {{ ad(number_format($minPrice)) }}
                        {{ trans('posts.filters.range_to') }}
                        {{ ad(number_format($minPrice)) }}
                        )
                    </label>
                    <div class="slider-container"
                         data-min="{{ $minPrice }}"
                         data-max="{{ $maxPrice }}">
                        <div class="slider-self"></div>
                        <div class="pt10">
                            <span class="min-label pull-left"></span>
                            <span class="max-label pull-right"></span>
                        </div>
                    </div>
                </div>
            @endif
            {{ null , $allCategories = PostsServiceProvider::postsCategories($allPosts, 'slug') }}
            @if(count($allCategories) > 1)
            <hr class="small">
                <div class="field mb0 filter-checkbox" data-identifier="category">
                    <label class="label-big"> {{ trans('front.categories') }} </label>
                    @foreach($allCategories as $categoryId => $categoryTitle)
                        <div class="checkbox blue mb10">
                            <input id="category-{{ $categoryId }}" value="{{$categoryId}}" type="checkbox"
                                   checked="checked">
                            <label for="category-{{ $categoryId }}"> {{ $categoryTitle }} </label>
                        </div>
                    @endforeach
                </div>
            @endif
            <hr class="small">
            <div class="field with-switch filter-switch filter-switch-checkbox" data-identifier="available">
                <label for="switch-a-5"> {{ trans('posts.filters.available') }}</label>
                <div class="switch d-ib blue">
                    <input id="availability" type="checkbox">
                    <label for="availability"></label>
                </div>
            </div>
            <div class="field with-switch filter-switch filter-switch-checkbox" data-identifier="special-sale">
                <label for="switch-a-5"> {{ trans('posts.filters.special_sale') }}</label>
                <div class="switch d-ib blue">
                    <input id="special_sale" type="checkbox">
                    <label for="special_sale"></label>
                </div>
            </div>
            <hr class="small">
            <div class="text-center">
                <a class="green button-link" href="#">
                    <i class="fa fa-refresh"></i>
                    {{ trans('posts.filters.reset_filters') }}
                </a>
            </div>
        </article>
    </div>
</div>