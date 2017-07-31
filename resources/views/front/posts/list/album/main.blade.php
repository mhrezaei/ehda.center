<div class="row mt20 mb20 gallery-archive">
    @if($posts and $posts->count())
        @foreach($posts as $post)
            @include($viewFolder . '.item')
        @endforeach
    @endif
</div>

@section('endOfBody')
    <script type="text/javascript">
        $(document).ready(function () {
            $("#gallery a").featherlightGallery({
                openSpeed: 300
            });
            $.featherlightGallery.prototype.afterContent = function () {
                var caption = this.$currentTarget.find('img').attr('alt');
                this.$instance.find('.featherlight-caption').remove();
                $('<h4 class="featherlight-caption text-right">').text(caption).appendTo(this.$instance.find('.featherlight-content'));
            };
        });
    </script>
@append
