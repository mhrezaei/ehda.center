{{ null, $post->spreadMeta() }}
@include($viewFolder . '.head')

<div class="row">
    <div class="col-md-8 col-md-offset-2 col-xs-12 col-xs-offset-0">
        <h3>{{ $post->title }}</h3>
        <div class="row">
            @include($viewFolder . '.player')
        </div>
        <p>{!! $post->abstract !!}</p>
    </div>
</div>
<div class="col-xs-12 border-top-2 border-top-gray pt20">
    @include('front.posts.single.post.post_footer', ['viewFolder' => 'front.posts.single.post'])
</div>
</div>

@include($viewFolder . '.scripts')