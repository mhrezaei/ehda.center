{{ null, $post->spreadMeta() }}
<div class="item text-start">
    <a href="{{ $post->direct_url }}" target="_blank">
        @php
            $thumbImage = UploadServiceProvider::changeFileUrlVersion($post->viewable_featured_image, 'slider-small')
        @endphp
        <img src="{{ url($thumbImage) }}">
        @if($post->title or $post->abstract)
            <div class="slide-text">
                @if($post->title)
                    <h4>{{ ad($post->title) }}</h4>
                @endif
                @if($post->abstract)
                    <span>{{ ad($post->abstract) }}</span>
                @endif
            </div>
        @endif
    </a>
</div>