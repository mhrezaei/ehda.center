@php
    $files = array_filter($downloadingFiles, function ($item) use ($aparatLinkPrefix) {
        return is_null(getAparatId($item['link']));
    });
    $files = array_values($files);
@endphp
<div class="row">
    <div class="col-xs-12 mt20 well download-box bg-lightGray text-blue">
        <h4 class="text-blue">{{ trans('front.download_links') }}</h4>
            @if($files and is_array($files) and count($files))
                <p>
                    @foreach($files as $key => $downloadingFile)
                        @php
                            $fileObj = \App\Providers\UploadServiceProvider::smartFindFile($downloadingFile['src']);
                        @endphp
                        @if($fileObj->exists)
                            @php
                                $fileObj->spreadMeta();
                                $linkTitle = $downloadingFile['label'] ?: $fileObj->file_name;
                                $url = route('file.download', [$downloadingFile['src'], $downloadingFile['label']]);
                            @endphp
                            @if($key) <br/> @endif
                                <div style="display: inline-block">
                                    <a target="_blank" href="{{ $url }}">
                                        <span class="fa fa-download"></span>
                                        {{ $linkTitle }}
                                    </a>
                                </div>
                                <div style="display: inline-block;">
                                    &nbsp;-&nbsp;
                                    {{ formatBytes($fileObj->size) }}
                                </div>
                        @endif
                    @endforeach
                </p>
            @endif
        {{--<div class="download-box-watermark">--}}
        {{--<span class="fa fa-download"></span>--}}
        {{--</div>--}}
    </div>
</div>