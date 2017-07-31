{{ null, $post->spreadMeta() }}

<div class="row">
    <div class="col-xs-12">
        <div class="col-md-4 col-xs-12">
            <h3>{{ $post->title }}</h3>
            <p>{!! $post->abstract !!}</p>
        </div>
        <div class="col-md-8 col-xs-12">
            <div class="row">
                @include($viewFolder . '.player')
            </div>
        </div>
    </div>
    <div class="col-xs-12 border-top-2 border-top-gray pt20">
        @include('front.posts.single.post.post_footer', ['viewFolder' => 'front.posts.single.post'])
    </div>
</div>

@include($viewFolder . '.scripts')