<div class="page uploader" id="upload" style="display: non;">
    {{--<div class="uploader-container">--}}
    {{--<h1>برای بارگذاری فایل خود را به اینجا بکشید.</h1>--}}
    {{--<p class="upload-notice">--}}
    {{--حداکثر حجم مجاز برای آپلود: 30 MB.--}}
    {{--</p>--}}
    {{--</div>--}}
    <div class="col-xs-12">
        <div class="row">
            @php $uploaderFilePaths = ['manager.__posttype__.image', 'manager.__posttype__.video'] @endphp
            {!! \App\Providers\FileManagerServiceProvider::dropzoneUploader($uploaderFilePaths) !!}
        </div>

        <div class="row files-uploading-status">
            {{--<div class="media-uploader-status">--}}
                {{--<h2>در حال بارگذاری...</h2>--}}
                {{--<button type="button" class="fa fa-times-circle upload-dismiss"></button>--}}
                {{--<div class="media-progress">--}}
                    {{--<div></div>--}}
                {{--</div>--}}
                {{--<div class="upload-detail">--}}
            {{--<span class="upload-count">--}}
            {{--<span class="upload-index">1</span>--}}
            {{--/--}}
            {{--<span class="upload-total">1</span>--}}
            {{--</span>--}}
                    {{--<span class="upload-detail-separator">_</span>--}}
                    {{--<span class="upload-filename">blabla.jpg</span>--}}
                {{--</div>--}}
                {{--<div class="upload-errors">--}}
                    {{--<ul>--}}
                        {{--<li>Error 1</li>--}}
                        {{--<li>Error 2</li>--}}
                    {{--</ul>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
</div>