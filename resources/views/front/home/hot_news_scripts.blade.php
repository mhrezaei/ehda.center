@section('endOfBody')
    <script>
        $(document).ready(function () {
            $(".news-slider").owlCarousel({
                autoPlay: 3000,
                stopOnHover: false,
                navigation: true,
                paginationSpeed: 1000,
                goToFirstSpeed: 2000,
                singleItem: true,
                transitionStyle: "fade",
                theme: "owl-card-theme",
                navigationText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>']
            });
        });
    </script>
@append