@if($downloadingFiles and is_array($downloadingFiles))
    @php
        $videos = array_filter($downloadingFiles, function ($item) use ($aparatLinkPrefix) {
            return starts_with(strtolower($item['link']), $aparatLinkPrefix);
        });
        $videos = array_values($videos);
        array_walk($videos, function (&$item) {
            $item['link'] = str_after($item['link'], 'v/');
        });
    @endphp

    @if(count($videos))
        <div class="row">
            <div class="col-md-8 col-md-offset-2 col-xs-12 col-xs-offset-0">
                <div class="row">
                    <div class="col-xs-12">
                        <div id="player-div"></div>
                    </div>
                    @include($viewFolder . '.aparat-player-playlist')
                    @include($viewFolder . '.aparat-player-scripts')
                </div>
            </div>
        </div>
    @endif
@endif