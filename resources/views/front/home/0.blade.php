@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.home') }}</title>
    @include('front.frame.open_graph_meta_tags', ['description' => $about->abstract])
@endsection

@section('content')
    @include('front.home.slider')
    @include('front.home.mouse_spacer')
    @include('front.home.about')
    @include('front.home.categories')
    @include('front.home.drawing')
    @include('front.home.comments')
@endsection