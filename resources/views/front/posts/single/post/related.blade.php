{{--{{ dd($post->relateds(4)) }}--}}
{{ null, $relatedPosts = $post->similars(4) }}

@if($relatedPosts->count())

    <div class="col-xs-12 mb30">
        <div class="row">
            <div class="col-xs-12">
                @include('front.frame.underlined_heading', [
                    'text' => trans('posts.features.dont_miss'),
                    'color' => 'green',
                ])
            </div>
            <div class="col-xs-12">
                <div class="row">
                    @foreach($relatedPosts as $relatedPost)
                        {{ null, $relatedPost->spreadMeta() }}
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="post-card">
                                <a href="{{ $relatedPost->direct_url }}" target="_blank">
                                    <img src="{{ $relatedPost->viewable_featured_image }}" class="post-card-image">
                                    <div class="post-card-text">
                                        <h4>{{ $relatedPost->title }}</h4>
                                        @if($relatedPost->abstract)
                                            <p class="text-justify">{{ str_limit($relatedPost->abstract, 110) }}</p>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


@endif