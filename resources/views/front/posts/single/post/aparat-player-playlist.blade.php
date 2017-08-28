<div class="col-xs-12 player-list pt10 pb10">
    @foreach($videos as $key => $videoThumb)
        @php
            $videos[$key]['link'] = str_after($videoThumb['link'], 'v/');
        @endphp
        @include($viewFolder . '.aparat-player-playlist-item')
    @endforeach
</div>