<div class="col-md-3 col-sm-4 col-xs-6 player-list-item @if($key == 0) current @endif"
     data-hashid="{{ $videoThumb['link'] }}">
    <div class="player-list-item-inner">
        {{--<img src="{{ url($videoThumb['src']) }}">--}}
        {!! \App\Providers\UploadServiceProvider::getFileView($videoThumb['src'], 'thumbnail') !!}
        <div class="player-list-item-text align-vertical-center align-horizontal-center">
            <p>
                {{ $videoThumb['label'] }}
                <br/>
                <span class="player-list-item-playing">
                <span class="fa fa-video-camera"></span>
                    {{ trans('front.media.playing') }}
            </span>
            </p>
        </div>
    </div>
</div>