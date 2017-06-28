<div class="owl-carousel news-slider" dir="ltr">
    @foreach($hotNews as $post)
        @include('front.home.hot_news_item')
    @endforeach
</div>
@include('front.home.hot_news_scripts')