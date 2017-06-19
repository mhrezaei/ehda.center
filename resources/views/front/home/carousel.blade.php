{!! Html::script ('assets/libs/owl.carousel/js/owl.carousel.min.js') !!}
<div class="row">
    <div class="owl-carousel home-slider" dir="ltr">
        <div class="item" dir="rtl">
            <a href="#" target="_blank">
                <img src="https://ehda.center/assets/photos/posts/slideshow/ED21600x600.jpg">
                {{--<div class="slide-text">--}}
                {{--<h3>«این لحظه خوش را از تو دارم»</h3>--}}
                {{--<span class="slide-text-description">بهرام فرهادی ۳۸ ساله - گیرنده ریه</span>--}}
                {{--</div>--}}
            </a>
        </div>
        <div class="item" dir="rtl">
            <a href="#" target="_blank">
                <img src="https://ehda.center/assets/photos/posts/slideshow/afshin.jpg">
                <div class="slide-text">
                    <h3>«این لحظه خوش را از تو دارم»</h3>
                    <span class="slide-text-description">
                        بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه                        بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه                         بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه                         بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه                         بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه                         بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه                         بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه                         بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه بهرام فرهادی ۳۸ ساله - گیرنده ریه
                    </span>
                </div>
            </a>
        </div>
        <div class="item" dir="rtl">
            <a href="#" target="_blank">
                <img src="https://ehda.center/assets/photos/posts/slideshow/s02.jpg">
                <div class="slide-text">
                    <h3>«این لحظه خوش را از تو دارم»</h3>
                    <span class="slide-text-description">بهرام فرهادی ۳۸ ساله - گیرنده ریه</span>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(".home-slider").owlCarousel({
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