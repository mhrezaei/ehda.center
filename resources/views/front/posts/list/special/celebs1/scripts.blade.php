@section('endOfBody')
    {!! Html::script ('assets/js/jquery.lazyload.min.js') !!}
    {!! Html::script ('assets/js/newWaterfall.js') !!}
    <script type="text/javascript">
        $(function () {
            var itemsSelector = '.yt-gallery-item';
            var imagesSelectorTxt = 'img.lazy';
            var items = $(itemsSelector);
            var imageElements = items.find(imagesSelectorTxt);
            imageElements.lazyload();

            items.mousemove(function () {
                doFocus($(this));
            });
            items.mouseleave(function () {
                items.removeClass('focus');
                items.removeClass('unfocus');
            });

            $('#waterfall').NewWaterfall({
                width: 150,
                delay: 100,
            });
        });
        function doFocus(element) {
            if (element.find('img.yt-gallery-item-img').hasClass('loaded')) {
                element.addClass('focus');
                element.siblings('.yt-gallery-item').addClass('unfocus')
            }
        }
    </script>
@endsection
