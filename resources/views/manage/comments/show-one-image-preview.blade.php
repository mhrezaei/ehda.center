<div class="col-md-4 col-sm-6 col-xs-12">
    <a href="{{ route('file.download', ['hashid' => $fileHashid]) }}">
        {!! \App\Providers\UploadServiceProvider::getFileView($fileHashid, 'thumbnail', ['class' => 'img-responsive']) !!}
    </a>
</div>
