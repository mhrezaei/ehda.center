@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.news') }}</title>
@endsection

@section('navbar')
    @include('front.frame.navbar',
    [
        'array' =>
        [
            [trans('front.home'), url_locale('')],
            [trans('front.news'), url_locale('news')],
        ],
    ])
@endsection

@section('content')
    @include('front.news.archive.content')
@endsection