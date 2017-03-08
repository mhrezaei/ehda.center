@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.home') }}</title>
@endsection

@section('content')
    @include('front.home.slider')
    @include('front.home.mouse_spacer')
    @include('front.home.about')
    @include('front.home.categories')
    @include('front.home.drawing')
@endsection