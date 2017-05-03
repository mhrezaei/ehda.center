{{ '' , $post->spreadMeta() }}
{{--{{ dd($post) }}--}}
<div class="page-content product-single">
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                @include($viewFolder . '.image')
            </div>
            <div class="col-sm-7 product-detials">
                <h2 class="product-name"> {{ $post->title }} </h2>
                <div class="excerpt"> {!! $post->abstract !!}</div>
                <hr>
                @include($viewFolder . '.price')
                @include($viewFolder . '.add_to_cart')
                <hr>
                @include($viewFolder . '.meta')
            </div>
        </div>
        <hr>
        @include($viewFolder . '.content')
        @include($viewFolder . '.similar_posts')
    </div>
</div>