@if(sizeof($categories))
    <div id="categories">
        <div class="container">
            <div class="part-title">
                <h3><span>{{ trans('front.categories_of') }}</span> {{ trans('front.products') }} </h3></div>
            <div class="cats">
                <div class="row">
                    @foreach($categories as $cat)
                        {{ '', $cat->spreadMeta() }}
                        <div class="col-sm-4">
                            <a href="{{ url_locale('products/categories/' . $cat->slug) }}" class="category-item">
                                <img src="{{ ($cat->image ? url($cat->image) : '#' ) }}">
                                <div class="more">
                                    <svg width="100%" height="100%" viewBox="0 0 220 42" version="1.1"
                                         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <polygon id="Rectangle" stroke="none" fill-opacity="0.802026721" fill="#000000"
                                                 fill-rule="evenodd"
                                                 points="0 0 220 0 178 42 3.55271368e-15 42"></polygon>
                                    </svg>
                                    <span> {{ trans('front.show_products') }} </span></div>
                                <div class="cat-name">
                                    <svg width="100%" height="100%" viewBox="0 0 220 42" version="1.1"
                                         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <polygon id="Rectangle" stroke="none" fill-opacity="0.802026721" fill="#31415B"
                                                 fill-rule="evenodd" points="42 0 220 0 220 42 0 42"></polygon>
                                    </svg>
                                    <span> {{ $cat->title }} </span></div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif