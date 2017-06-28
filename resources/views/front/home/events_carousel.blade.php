@if($eventsSlideShow and $eventsSlideShow->count())
    <div class="row">
        <div class="owl-carousel home-slider events-slider" dir="ltr">
            @foreach($eventsSlideShow as $post)
                @include('front.home.events_carousel_item')
            @endforeach
        </div>
    </div>
@endif