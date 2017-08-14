@extends('manage.frame.layouts.plane')

@section('body')
    <div class="container">
        <input type="hidden" id="upload-result"/>
        <script>
            function test(file) {
                console.log(file)
                alert('yeeeeeeeeeeeees')
            }
        </script>
        {{--{!! \App\Providers\FileManagerServiceProvider::dropzoneUploader('video') !!}--}}
        @php
            $uploaderData = [
                'target' => 'upload-result',
                'callbackOnAllUploadsComplete' => 'test',
            ]
        @endphp
        {!! \App\Providers\FileManagerServiceProvider::posttypeUploader('word-news', $uploaderData) !!}
    </div>
@append