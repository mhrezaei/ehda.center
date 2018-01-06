@section('endOfBody')
    <script type="text/JavaScript" id="video-script"
            src="https://www.aparat.com/embed/{{ $files[0]['link'] }}?data[rnddiv]=player-div&data[responsive]=yes">
    </script>

    <script>
        $(document).ready(function () {
            $('.player-list-item-inner').click(function () {
                var item = $(this).closest('.player-list-item');
                if (!item.hasClass('current')) {
                    var hashid = item.attr('data-hashid');
                    var newSrc = "https://www.aparat.com/embed/" +
                        hashid +
                        "?data[rnddiv]=player-div&data[responsive]=yes";
                    var currentScript = $('#video-script');

                    var newScript = document.createElement("script");
                    newScript.type = "text/javascript";
                    newScript.id = 'video-script';
                    newScript.src = newSrc;
                    currentScript.after(newScript);
                    currentScript.remove();

                    $('.player-list-item.current').removeClass('current');
                    item.addClass('current');
                }
            });
        });
    </script>
@append