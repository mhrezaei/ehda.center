@extends('front.frame.frame')

@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . trans('front.home') @endphp
@section('head')
    <title>{{ $pageTitle }}</title>
@append
@php $metaTags = [ 'title' => $pageTitle ] @endphp
@include('front.frame.meta_tags')
@include('front.frame.open_graph_meta_tags')

@section('content')
    {!! Html::script ('assets/libs/owl.carousel/js/owl.carousel.min.js') !!}
    <div class="container-fluid">
        @include('front.home.main_carousel')
        @include('front.home.pending_organ_transplant')
        @include('front.home.events_carousel')
        @include('front.home.home_notes')
        @include('front.home.equation')
        @include('front.home.hot_links')
        @include('front.home.carousel_scripts')
    </div>
@endsection