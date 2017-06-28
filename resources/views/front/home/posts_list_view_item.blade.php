{{ null, $post->spreadMeta() }}
<div class="media-list-item {{ $itemsColorClass }}">
    <a href="{{ $post->direct_url }}">
        <div class="media-list-item-image">
            <img src="{{ url($post->featured_image) }}" class="media-object">
        </div>
        <div class="media-list-item-body">
            <h5 class="media-list-item-heading">
                {{ $post->title }}
            </h5>
            <p class="text-gray text-end">{{ ad(echoDate($post->published_at, 'j F y')) }}</p>
        </div>
    </a>
</div>