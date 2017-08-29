@if($post->has('download') and $downloadingFiles and is_array($downloadingFiles))
    @if($post->canDownloadFile() or $messagesPosts['noAccessFiles']->exists)
        @if($post->canDownloadFile())
            @include($viewFolder . '.aparat-player')
            @include($viewFolder . '.download-box')
        @else
            <div class="row">
                <div class="col-xs-12 mt20 well download-box bg-lightGray text-blue">
                    <h4 class="text-blue">{{ trans('front.download_links') }}</h4>
                    <div class="alert alert-danger">
                        {!! $messagesPosts['noAccessFiles']->text !!}
                    </div>
                </div>
            </div>
        @endif
    @endif
@endif