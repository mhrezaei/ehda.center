<div class="page uploader" id="upload" style="display: none;">
    {{--<div class="uploader-container">--}}
    {{--<h1>برای بارگذاری فایل خود را به اینجا بکشید.</h1>--}}
    {{--<p class="upload-notice">--}}
    {{--حداکثر حجم مجاز برای آپلود: 30 MB.--}}
    {{--</p>--}}
    {{--</div>--}}
    <div class="col-xs-12">
        @php
            $uploaderFilePaths = [
                'manager.__posttype__.image',
                'manager.__posttype__.video',
                'manager.__posttype__.audio',
                'manager.__posttype__.text',
            ]
        @endphp
        {!! \App\Providers\FileManagerServiceProvider::dropzoneUploader($uploaderFilePaths, [
            'directUpload' => true,
            'callbackOnEachUploadComplete' => 'eachUploadCompleter',
        ]) !!}
    </div>
</div>