@extends('front.frame.frame')

@if(isset($pageTitle))
    @php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . $pageTitle @endphp
@else
    @php $pageTitle = setting()->ask('site_title')->gain()  @endphp
@endif
@section('head')
    <title>{{ $pageTitle }}</title>
@append
@php $metaTags['title'] = $pageTitle @endphp
@include('front.frame.meta_tags')
@include('front.frame.open_graph_meta_tags')

@if(!isset($positionInfo))
    {{ null, $positionInfo = [] }}
@endif

@section('content')
    <div class="container-fluid">
        @include('front.frame.position_info', $positionInfo + [
            'groupColor' => 'green',
            'categoryColor' => 'green',
        ])
        <div class="container content">
            {!! $innerHTML !!}
        </div>
    </div>
@endsection