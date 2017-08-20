@if($post->canDownloadFile())
    <div class="row">
        <div class="col-xs-12 mt20 well bg-lightGray text-blue">
            <h5 class="text-blue">{{ trans('front.download_links') }}</h5>
            <p>
                @php $downloadingFiles = $post->post_files @endphp
                @if($downloadingFiles and is_array($downloadingFiles) and count($downloadingFiles))
                    @foreach($downloadingFiles as $key => $downloadingFile)
                        @php
                            $fileObj = \App\Providers\UploadServiceProvider::smartFindFile($downloadingFile['src']);
                        @endphp
                        @if($fileObj->exists)
                            @php
                                $fileObj->spreadMeta();
                                $linkTitle = $downloadingFile['label'] ?: $fileObj->original_name;
                                $url = route('file.download', [$downloadingFile['src'], $downloadingFile['label']]);
                            @endphp
                            @if($key) <br/> @endif
                            <a target="_blank" href="{{ $url }}">{{ $linkTitle }}</a>
                        @endif
                    @endforeach
                @endif
            </p>
        </div>
    </div>
@endif