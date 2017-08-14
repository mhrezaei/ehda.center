@extends('manage.frame.layouts.plane')

@section('body')
    <div class="container">
        {{--{!! \App\Providers\FileManagerServiceProvider::dropzoneUploader('video') !!}--}}
        {!! \App\Providers\FileManagerServiceProvider::posttypeUploader('word-news') !!}
    </div>
@append