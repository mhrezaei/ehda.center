@if($hotNews and $hotNews->count())
    <div class="owl-carousel news-slider" dir="ltr">
        @foreach($hotNews as $post)
            @include('front.home.hot_news_item')
        @endforeach
    </div>
@endif
@include('front.home.hot_news_scripts')