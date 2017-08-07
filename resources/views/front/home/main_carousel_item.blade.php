{{ null, $post->spreadMeta() }}
{{--{{ dd($post->featured_image) }}--}}
<div class="item" dir="rtl">
    {{-- If the link is specified, the item will behave like a link. --}}
    <a @if($post->link) href="{{ $post->link }}" target="_blank" @endif>
        <img src="{{ url($post->featured_image) }}">
        @if($post->title2 or $post->abstract)
            <div class="slide-text">
                @if($post->title2)
                    <h3>{{ ad($post->title2) }}</h3>
                @endif
                @if($post->abstract)
                    <span class="slide-text-description">
                    {{ ad($post->abstract) }}
                </span>
                @endif
            </div>
        @endif
    </a>
</div>