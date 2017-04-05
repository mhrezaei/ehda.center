@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.products') }}</title>
@endsection

@section('navbar')
    @include('front.frame.navbar',
    [
        'array' =>
        [
            [trans('front.home'), url_locale('')],
            [trans('front.products'), url_locale('products')],
            [$folder->title, url_locale('products/categories/' . $folder->slug)],
        ],
        'title' => $folder->title
    ])
@endsection

@section('content')
    @include('front.products.products.content')
@endsection