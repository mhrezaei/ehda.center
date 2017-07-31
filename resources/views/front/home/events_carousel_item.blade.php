{{ null, $post->spreadMeta() }}
<div class="item">
    <a @if($post->link) href="{{ $post->link }}" target="_blank" @endif>
        <img src="{{ url($post->featured_image) }}">
        @if($post->title2 or $post->abstract or $post->link)
            <div class="event-box">
                <div class="text">
                    @if($post->title)
                        <h2>{{ $post->title }}</h2>
                    @endif
                    <p>
                        @if($post->abstract)
                            {{ $post->abstract }}
                        @endif
                        &nbsp;
                        @if($post->link)
                            ({{ trans('front.continue') }})
                        @endif
                    </p>
                </div>
            </div>
        @endif
    </a>
</div>