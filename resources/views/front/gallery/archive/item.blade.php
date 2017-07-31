{{ null, $item->spreadMeta() }}

<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
    <a href="{{ $item->direct_url ?: '#' }}" class="thumbnail">
        @if($item->image)
            <img class="media-object" src="{{ url($item->image) }}" alt="{{ $item->title }}">
        @endif
        <span class="media-title">{{ $item->title }}</span>
    </a>
</div>