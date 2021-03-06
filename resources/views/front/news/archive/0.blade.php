@extends('front.frame.frame')

@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . trans('front.news') @endphp
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
    ])
@endsection

@section('content')
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-center">
                    {!! $newsListHTML !!}
                </div>
            </div>
        </div>
    </div>
@endsection