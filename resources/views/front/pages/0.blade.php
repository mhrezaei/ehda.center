@extends('front.frame.frame')

@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . $page->title @endphp
@section('head')
    <title>{{ $pageTitle }}</title>
@endsection
@include('front.frame.meta_tags', [
    'metaTags' => [
        'title' => $pageTitle,
    ]
])
@include('front.frame.open_graph_meta_tags', [
    'metaTags' => $ogData
])

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