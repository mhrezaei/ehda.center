@extends('front.frame.frame')

@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . trans('front.states') @endphp
@section('head')
    <title>{{ $pageTitle }}</title>
@append
@php $metaTags = [ 'title' => $pageTitle ] @endphp
@include('front.frame.meta_tags')
@include('front.frame.open_graph_meta_tags')

@section('content')
    <div class="container-fluid">
        @include('front.frame.position_info', array_merge([
            'groupColor' => 'green'
        ], $positionInfo))
        <div class="container">
            <div class="row">
                @include('front.iranmap.state-info')
                @include('front.iranmap.map')
            </div>
        </div>
    </div>
@endsection

@include('front.iranmap.scripts')