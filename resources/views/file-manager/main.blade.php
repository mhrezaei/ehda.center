@extends('file-manager.frame.frame')

@section('body')
    <div class="media-content">
        @include('file-manager.folder-menu')
        @include('file-manager.media-frame')
    </div>
@append