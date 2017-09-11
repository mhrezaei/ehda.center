@extends('front.frame.frame')

@if(!isset($pageTitle))
    @php $pageTitle = setting()->ask('site_title')->gain() @endphp
@else
    @php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . $pageTitle @endphp
@endif
@php $metaTags = [ 'title' => $pageTitle ] @endphp
@include('front.frame.meta_tags')
@include('front.frame.open_graph_meta_tags')

@section('head')
    <title>{{ $pageTitle }}</title>
@endsection

@if(!isset($positionInfo))
    @php $positionInfo = []  @endphp
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