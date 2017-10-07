<div class="row" id="thissss">
    @php
        $post->spreadMeta();
        $featuredImageUrl = \App\Providers\UploadServiceProvider::changeFileUrlVersion($post->viewable_featured_image, 'original');
    @endphp
    <div class="col-md-4 col-xs-12">
        <img class="img-responsive" src="{{ $featuredImageUrl }}" />
    </div>
    <div class="col-md-8 col-xs-12">
        <h4>{{ $post->title }}</h4>
        @php
            $post->biography = str_replace("\n", "<br />", $post->biography);
        @endphp
        <p>{!! $post->biography !!}</p>
    </div>

    <div class="col-xs-12 mt10 mb20">
        {!! $post->text !!}
    </div>
</div>