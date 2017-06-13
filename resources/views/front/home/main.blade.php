@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.home') }}</title>
@endsection

@section('content')
    {!! Html::script ('assets/libs/owl.carousel/js/owl.carousel.min.js') !!}
    <div class="container-fluid">
        @include('front.home.carousel')
        @include('front.home.current-members')
        @include('front.home.events-carousel')
        @include('front.home.home-notes')
        @include('front.home.equation')
        @include('front.home.hot-links')
    </div>
@endsection