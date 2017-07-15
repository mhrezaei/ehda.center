@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ $pageTitle or '' }}</title>
@endsection

@if(!isset($positionInfo))
    {{ null, $positionInfo = [] }}
@endif

@section('content')
    <div class="container-fluid">
        @include('front.frame.position_info', $positionInfo + [
            'groupColor' => 'green',
            'categoryColor' => 'green',
        ])
        {!! $innerHTML !!}
    </div>
@endsection