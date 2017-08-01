@extends('front.frame.frame')

@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . trans('front.products') @endphp
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
        'array' => $breadCrumb,
        'title' => end($breadCrumb)[0],
    ])
@endsection

@section('content')
    <div class="page-content product-single">
        <div class="container">
            {!! $postHTML !!}
        </div>
    </div>
@endsection