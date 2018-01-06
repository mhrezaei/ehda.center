@php
    $post->spreadMeta();
    $files = $post->files;
@endphp
@include($viewFolder . '.head')

@foreach($files as $key => $file)
    @php $files[$key]['link'] = getAparatId($file['link']) @endphp
@endforeach

<div class="row">
    <div class="col-md-8 col-md-offset-2 col-xs-12 col-xs-offset-0">
        <h3>{{ $post->title }}</h3>
        <div class="row">
            @include($viewFolder . '.player')
        </div>
        <p class="text-justify">{!! $post->abstract !!}</p>
    </div>
</div>
<div class="col-xs-12 pt20">
    @include('front.posts.single.post.post_footer', ['viewFolder' => 'front.posts.single.post'])
</div>

@include($viewFolder . '.scripts')