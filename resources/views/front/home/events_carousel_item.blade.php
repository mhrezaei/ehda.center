@php $post->spreadMeta() @endphp
@php $image = $post->viewable_featured_image @endphp
@if($image)
    <div class="item">
        <img src="{{ $image }}">
        @if($post->title2 or $post->abstract)
            <div class="event-box">
                <div class="text">
                    @if($post->title)
                        <h2>{{ ad($post->title) }}</h2>
                    @endif
                    <p>
                        @if($post->abstract)
                            {{ ad($post->abstract) }}
                        @endif
                        &nbsp;
                        @if($post->link)
                            <a @if($post->link) href="{{ $post->link }}" target="_blank" @endif>
                                ({{ trans('front.continue') }})
                            </a>
                        @endif
                    </p>
                </div>
            </div>
        @endif
    </div>
@endif
