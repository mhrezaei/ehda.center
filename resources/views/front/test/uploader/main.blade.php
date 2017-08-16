@extends('manage.frame.layouts.plane')

@section('body')
    <div class="container">
        <div class="row">
            {!! \App\Providers\UploadServiceProvider::getFileView(149, 'thumbnail', [
                'style' => [
                    'border-radius' => '10px',
                    'height' => '200px',
                    'width' => '400px',
                ],
                'class' => [
                    'any-class'
                ],
                'otherAttributes' => [],
                'dataAttributes' => [],
                'extra' => '',
            ]) !!}
            {!! \App\Providers\UploadServiceProvider::getFileView(150, 'thumbnail', [
                'style' => [
                    'border-radius' => '10px',
                    'height' => '100px',
                    'width' => '100px',
                ],
                'class' => [
                    'any-class'
                ],
                'otherAttributes' => [],
                'dataAttributes' => [],
                'extra' => '',
            ]) !!}
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