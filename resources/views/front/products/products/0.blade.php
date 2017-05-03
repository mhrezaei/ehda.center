@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.products') }}</title>
@endsection

@section('navbar')
    @include('front.frame.navbar',
    [
        'array' => $breadCrumb,
        'title' => end($breadCrumb)[0],
    ])
@endsection

@section('content')
    {!! PostsServiceProvider::showList($selectConditions) !!}
@endsection