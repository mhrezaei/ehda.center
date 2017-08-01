@extends('front.frame.frame')

@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . trans('front.products') @endphp
@section('head')
    <title>{{ $pageTitle }}</title>
@append
@php $metaTags = [ 'title' => $pageTitle ] @endphp
@include('front.frame.meta_tags')
@include('front.frame.open_graph_meta_tags')

@section('navbar')
    @include('front.frame.navbar',
    [
        'array' =>
        [
            [trans('front.home'), url_locale('')],
            [trans('front.products'), url_locale('products')],
        ]
    ])
@endsection
@section('content')
    @include('front.products.folders.content')
@endsection