@if(isset($slideShow) and $slideShow and $slideShow->count())
@section('endOfBody')
    {!! Html::script ('assets/libs/owl.carousel/js/owl.carousel.min.js') !!}
@append
<div class="row">
    <div class="col-xs-12">
        <div class="owl-carousel home-slider" dir="ltr">
            @foreach($slideShow as $post)
                @include('front.tutorials.archive.carousel_item')
            @endforeach
        </div>
    </div>
</div>

@section('endOfBody')
    <script>
        $(document).ready(function () {
            $(".owl-carousel").owlCarousel({
                autoPlay: 3000,
                stopOnHover: false,
                navigation: true,
                paginationSpeed: 1000,
                goToFirstSpeed: 2000,
                singleItem: true,
                transitionStyle: "fade",
                afterInit: function () {
                    this.$owlItems.each(function () {
                        var imgLink = $(this).find('img').first().attr('src');
                        var item = $(this).find('.item').first();
                        item.css('background-image', 'url(' + imgLink + ')');
                    });
                }
            });
        });
    </script>
@append

@endif
