@section('endOfBody')
    {!! Html::script ('assets/libs/owl.carousel/js/owl.carousel.min.js') !!}
    <script>
        $(document).ready(function () {
            $(".home-slider").owlCarousel({
//            autoPlay: 3000,
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