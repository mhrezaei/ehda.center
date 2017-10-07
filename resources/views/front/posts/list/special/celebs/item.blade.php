@php $post->spreadMeta() @endphp

<li class="yt-gallery-item">
    <div>
        <a href="{{ $post->direct_url }}">
            @php $thumbnailClass = $post->has_medal ? 'border-gold' : 'border-silver' @endphp
            <img class="yt-gallery-item-img {{ $thumbnailClass }} lazy"
                 data-original="{{ $post->viewable_featured_image }}"
                 alt="">
            <div class="yt-gallery-item-text">{{ $post->title }}</div>
        </a>
    </div>
</li>