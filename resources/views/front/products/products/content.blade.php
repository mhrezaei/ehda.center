<div class="page-content category">
    <div class="container">
        <header id="category-header">
            <div class="field search"> <input type="text" placeholder="{{ trans('front.search') }}"> <span class="icon-search"></span> </div>
            <div class="field sort"> <label> {{ trans('front.sort') }} </label>
                <div class="select rounded"> <select>
                        <option> {{ trans('front.price_max_to_min') }} </option>
                        <option> {{ trans('front.price_min_to_max') }} </option>
                        <option> {{ trans('front.best_seller') }} </option>
                        <option> {{ trans('front.favorites') }} </option>
                    </select> </div>
            </div>
        </header>
        <div class="row">
            @include('front.products.products.sidebar')
            <div class="col-sm-9">
                <div class="product-list">
                    <div class="row">
                        @if($products)
                            @foreach($products as $product)
                                {{ '', $product->spreadMeta() }}
                                <div class="col-sm-4">
                                    {{--<a href="#" class="product-item ribbon-new ribbon-sale">--}}
                                    <a href="#" class="product-item">
                                        <div class="thumbnail"><img src="{{ url($product->featured_image) }}"></div>
                                        <div class="content">
                                            <h6> {{ $product->title }} </h6>
                                            <div class="price">
                                                @if($product->price > 0)
                                                    <del>{{ pd(number_format($product->price)) }}</del>
                                                @endif
                                                @if($product->sale_price)
                                                    <ins>{{ pd(number_format($product->sale_price)) }} {{ trans('front.toman') }}</ins>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="pagination-wrapper mt20">
                        {!! $products->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>