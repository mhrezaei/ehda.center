{{ null, $post->spreadMeta() }}


<div class="row">
    <div class="col-xs-12">
        @if($post->abstract)
            <div class="col-xs-12">
                <p>
                    {{ $post->abstract }}
                </p>
            </div>
        @endif
    </div>
    <div class="flex_wrapper" id="gallery">
        @if($post['post_photos'] and is_array($post['post_photos']))
            @foreach($post['post_photos'] as $key => $item)
                @include($viewFolder . '.item')
            @endforeach
        @endif
    </div>
</div>

@section('endOfBody')
    @include('front.gallery.single.scripts')

    <script type="text/javascript">
        $(document).ready(function () {
            $('.flex-item img').css('opacity', 0).on('load', function () {
                $(this).css('opacity', '1');
            }).each(function () {
                if (this.complete) {
                    $(this).trigger('load');
                }
            });
            
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