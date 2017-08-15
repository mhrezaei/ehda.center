@extends('manage.frame.layouts.plane')

@section('body')
    <div class="container">
        <div class="row">
            {!! \App\Providers\UploadServiceProvider::getFileView(133) !!}
        </div>
        <input type="hidden" id="upload-result"/>
        <script>
            function test(file) {
                console.log(file)
                alert('yeeeeeeeeeeeees')
            }
        </script>
        @php
            $uploaderData = [
                'target' => 'upload-result',
                'callbackOnQueueComplete' => 'test',
            ]
        @endphp
        {!! \App\Providers\FileManagerServiceProvider::posttypeUploader('word-news', $uploaderData) !!}
    </div>
@append