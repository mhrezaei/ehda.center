<div class="blog-single">
    <div class="blog-cover"><img src="{{ $post->viewable_featured_image }}"></div>
    <div class="blog-header">
        @include($viewFolder . '.categories')
        <h1 class="title"> {{ $post->title }} </h1>
        @include($viewFolder . '.publish_info')
    </div>
    @include($viewFolder . '.content')
</div>
@include($viewFolder . '.related_posts')