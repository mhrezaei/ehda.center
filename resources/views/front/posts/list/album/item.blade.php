<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
    <a href="{{ $post->direct_url }}" class="thumbnail">
        <img class="media-object" src="{{ $post->viewable_featured_image_thumbnail }}" alt="{{ $post->title }}">
        <span class="media-title">{{ $post->title }}</span>
    </a>
</div>