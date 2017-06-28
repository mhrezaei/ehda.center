@if($mainSlideShow and $mainSlideShow->count())
    {!! Html::script ('assets/libs/owl.carousel/js/owl.carousel.min.js') !!}
    <div class="row">
        <div class="owl-carousel home-slider" dir="ltr">
            @foreach($mainSlideShow as $post)
                @include('front.home.main_carousel_item')
            @endforeach
        </div>
    </div>
@endif