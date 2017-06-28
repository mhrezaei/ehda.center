@if($posts)
    @foreach($posts as $post)
        {{ '', $post->spreadMeta() }}
        <div class="blog-item style-1">
            <div class="thumbnail">
                <a href="{{ $post->direct_url }}">
                    <img src="{{ $post->viewable_featured_image }}" alt="{{ $post->title }}">
                </a>
            </div>
            <div class="content">
                <a href="{{ $post->direct_url }}">
                    <h3 class="title"> {{ $post->title }} </h3>
                </a>
                <div class="excerpt">
                    <p>
                        {{ $post->abstract }}
                    </p>
                </div>
                <div class="action">
                    <a href="{{ $post->direct_url }}"
                       class="more">{{ trans('front.read_more') }}
                        <span class="icon-angle-right"></span>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
@endif
<div class="pagination-wrapper mt20">
    {!! $posts->render() !!}
</div>
