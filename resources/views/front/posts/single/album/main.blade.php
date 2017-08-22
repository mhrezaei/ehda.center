{{ null, $post->spreadMeta() }}

<div class="row">
    <div class="col-xs-12">
        <h3>{{ $post->title }}</h3>
        @if($post->abstract)
            <div class="col-xs-12">
                <p>
                    {{ $post->abstract }}
                </p>
            </div>
        @endif
    </div>
    <div class="col-xs-12">
        <div class="flex_wrapper" id="gallery">
            {{--@TODO: this file need to edit gallery files should load with service provider--}}
            @if($post->files and is_array($post->files))
                @foreach($post->files as $key => $item)
                    @include($viewFolder . '.item')
                @endforeach
            @endif
        </div>
    </div>
</div>
<div class="col-xs-12 pt20">
    @include('front.posts.single.post.post_footer', ['viewFolder' => 'front.posts.single.post'])
</div>

@section('endOfBody')
    @include($viewFolder . '.scripts')

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
                openSpeed: 300,
                previousIcon: '&#xf053;',
                nextIcon: '&#xf054;',
            });
            $.featherlightGallery.prototype.afterContent = function () {
                var caption = this.$currentTarget.find('img').attr('alt');
                this.$instance.find('.featherlight-caption').remove();
                $('<h4 class="featherlight-caption text-right">').text(caption).appendTo(this.$instance.find('.featherlight-content'));
            };
        });
    </script>
@append