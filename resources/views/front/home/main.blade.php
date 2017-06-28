@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.home') }}</title>
@endsection

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