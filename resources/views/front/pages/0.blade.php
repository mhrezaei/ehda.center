@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ $page->title }}</title>
@endsection

@section('navbar')
    @include('front.frame.navbar',
    [
        'array' =>
        [
            [trans('front.home'), url_locale('')],
            [$page->title, url_locale('page/about')],
        ],
        'title' => $page->title,
    ])
@endsection

@section('content')
    @include('front.pages.content')
@endsection