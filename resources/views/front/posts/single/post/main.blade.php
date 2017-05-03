<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-center">
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
            </div>
        </div>
    </div>
</div>