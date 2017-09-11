@section('endOfBody')
    <script>
        $('document').ready(function () {
            let urlHash = window.location.hash;
            let targetEl;

            if(urlHash && (targetEl = $(urlHash)).length) {
                let targetLocation = targetEl.offset().top - $('.main-menu').height() - 10;
                $("html, body").stop().animate({scrollTop:targetLocation}, 500, 'swing')
            }
        });
    </script>
@append