@extends('front.frame.frame')

@section('head')
    <title>{{ $pageTitle }}</title>
@endsection

@section('navbar')
    @include('front.frame.navbar',
    [
        'array' => $breadCrumb,
    ])
@endsection

@section('content')
    {!! PostsServiceProvider::showList($selectData) !!}
@endsection