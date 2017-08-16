@extends('file-manager.frame.frame')

@section('body')
    <div class="media-content">
        @include('file-manager.folder-menu')
        @include('file-manager.media-frame')
    </div>
@append

@section('end-of-body')
    <script>
        var route_prefix = "{{ url('/') }}";
{{--        var lfm_route = "{{ url(config('lfm.prefix')) }}";--}}
        var lang = {!! json_encode(trans('file-manager')) !!};
    </script>
@append