{{ null , $similarPosts = $mostExpensive->similars(4) }}
@if($similarPosts and $similarPosts->count())
    <div class="related">
        <div class="part-title">
            <h3>
                {!! trans('posts.features.maybe_you_like') !!}
            </h3>
        </div>
    </div>
    <div class="row">
        @foreach($similarPosts as $similarPost)
            <div class="col-sm-3">
                <a href="{{ $similarPost->direct_url }}" class="product-item">
                    <div class="thumbnail"><img src="{{ $similarPost->viewable_featured_image_thumbnail }}"></div>
                    <div class="content">
                        <h6> {{ $similarPost->title }} </h6>
                        <div class="price">
                            <del>{{ pd($similarPost->price) }} {{ trans('front.toman') }}</del>
                            <ins>{{ pd($similarPost->sale_price) }} {{ trans('front.toman') }}</ins>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endif