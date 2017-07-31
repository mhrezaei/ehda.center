<div class="col-xs-12 player-list pt10 pb10">
    @foreach($post->post_photos as $key => $videoThumb)
        @include($viewFolder . '.playlist-item')
    @endforeach
</div>