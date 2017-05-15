{{ null , $similarPosts = $post->similars(3) }}

@if($similarPosts and $similarPosts->count())
    <div class="part-title mt50">
        <h3>
            {!! str_replace('::things', trans('front.posts'), trans('posts.features.related_things')) !!}
        </h3>
    </div>
    <div class="row">
        @foreach($similarPosts as $similarPost)
            <div class="col-sm-4">
                <div class="blog-item style-2">
                    <a href="{{ $similarPost->direct_url }}">
                        <div class="thumbnail"><img src="{{ $similarPost->viewable_featured_image_thumbnail }}"></div>
                        <div class="content">
                            <h3 class="title">{{ str_limit($similarPost->title, 30) }}</h3>
                            <p class="text-gray f12">
                                {{ pd(echoDate($similarPost->published_at, 'd F Y')) }}
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif