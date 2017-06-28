{{ null, $post->spreadMeta() }}
<div class="item text-start">
    <a href="{{ $post->direct_url }}" target="_blank">
        <img src="{{ url($post->featured_image) }}">
        @if($post->title or $post->abstract)
            <div class="slide-text">
                @if($post->title)
                    <h4>{{ $post->title }}</h4>
                @endif
                @if($post->abstract)
                    <span>{{ $post->abstract }}</span>
                @endif
            </div>
        @endif
    </a>
</div>