@if(sizeof($categories))
    <div id="categories">
        <div class="container">
            <div class="part-title">
                <h3> <span>{{ trans('front.categories') }}</span> {{ trans('front.products') }} </h3> </div>
            <div class="cats">
                <div class="row">
                    @foreach($categories as $cat)
                        {{ '', $cat->spreadMeta() }}
                        <div class="col-sm-4">
                            <a href="{{ url_locale('products/categories/' . $cat->slug) }}" class="category-item">
                                <img src="{{ url($cat->image) }}">
                                <span class="cat-name"> {{ $cat->title }} </span>
                                <span class="more"> {{ trans('front.show_products') }} </span>
                            </a>
                        </div>
                    @endforeach
                </div>
                {{--<div class="row">--}}
                {{--<div class="col-sm-4">--}}
                {{--<a href="#" class="category-item"> <img src="{{ url('/assets/images/sample/category-item.jpg') }}"> <span class="cat-name"> پسته‌ی خام </span> <span class="more"> {{ trans('front.show_products') }} </span> </a>--}}
                {{--</div>--}}
                {{--<div class="col-sm-4">--}}
                {{--<a href="#" class="category-item"> <img src="{{ url('/assets/images/sample/category-item.jpg') }}"> <span class="cat-name"> پسته‌ی خام </span> <span class="more"> {{ trans('front.show_products') }} </span> </a>--}}
                {{--</div>--}}
                {{--<div class="col-sm-4">--}}
                {{--<a href="#" class="category-item"> <img src="{{ url('/assets/images/sample/category-item.jpg') }}"> <span class="cat-name"> پسته‌ی خام </span> <span class="more"> {{ trans('front.show_products') }} </span> </a>--}}
                {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
@endif