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
        ]
    ])
@endsection
@section('content')
    @include('front.products.folders.content')
@endsection