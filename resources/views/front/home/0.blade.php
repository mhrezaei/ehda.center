@extends('front.frame.frame')

@section('title')
    {{ trans('front.home') }}
@endsection

@section('content')
    @include('front.home.slider')
    @include('front.home.mouse_spacer')
    @include('front.home.about')
    @include('front.home.categories')
    @include('front.home.drawing')
@endsection