{{ null, $post->spreadMeta() }}
<div class="media @if($twoColumns) col-xs-6 @else col-xs-12 @endif">
    <a class="link-black" href="{{ $post->direct_url }}">
        <div class="media-start">
            <img src="{{ $post->viewable_featured_image }}">
        </div>
        <div class="media-body">
            <h4 class="media-heading">{{ $post->title }}</h4>
            <p class="text-justify">{{ $post->abstract }}</p>
            <p class="text-gray">{{ ad(echoDate($post->published_at, 'j F y')) }}</p>
        </div>
    </a>
</div>
