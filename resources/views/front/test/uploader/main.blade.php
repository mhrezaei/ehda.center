@extends('manage.frame.layouts.plane')

@section('body')
    <div class="container">
        {{--<div class="row">--}}
            {{--<div class="col-xs-12">--}}
{{--                {{ \App\Providers\UploadServiceProvider::getFileUrl(133) }}--}}
                {{--{{ \App\Providers\UploadServiceProvider::getFileView('edYNO') }}--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--{!! \App\Providers\FileManagerServiceProvider::dropzoneUploader('video') !!}--}}
        <script>
            function test() {
                alert('yyyyyyyyyyyyyysss');
            }
        </script>
        @php
            $uploaderData = [
                'callbackOnQueueComplete' => 'test',
            ];
        @endphp
        {!! \App\Providers\FileManagerServiceProvider::posttypeUploader('word-news', $uploaderData) !!}
    </div>
@append